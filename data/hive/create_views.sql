ADD JAR jsonserde.jar;

DROP VIEW tweets_simple;
DROP VIEW l1;
DROP VIEW l2;
DROP VIEW l3;
DROP VIEW tweets_sentiment;
DROP VIEW tweets_clean;

CREATE FUNCTION GetNlpSentiment AS 'je.hustings.hive.nlp.SentimentAnalyser';

-- Clean up tweets
CREATE VIEW tweets_simple AS
SELECT
  id,
  cast ( from_unixtime( unix_timestamp(concat( '2014 ', substring(created_at,5,15)), 'yyyy MMM dd hh:mm:ss')) as timestamp) ts,
  translate(text, '\n', ' ') as text,
  name,
  screen_name
FROM tweets_raw
;

-- Compute sentiment
create view l1 as select id, words from tweets_raw lateral view explode(sentences(lower(text))) dummy as words;
create view l2 as select id, word from l1 lateral view explode( words ) dummy as word ;

-- was: select * from l2 left outer join dict d on l2.word = d.word where polarity = 'negative' limit 10;

create view l3 as select 
    id, 
    l2.word, 
    case d.polarity 
      when  'negative' then -1
      when 'positive' then 1 
      else 0 end as polarity 
 from l2 left outer join dictionary d on l2.word = d.word;

  create view tweets_sentiment as select 
  id, 
  case 
    when sum( polarity ) > 0 then 'positive' 
    when sum( polarity ) < 0 then 'negative'  
    else 'neutral' end as sentiment 
 from l3 group by id;

 create view nlp_score as select
	id,
	GetNlpSentiment(text) as NlpSentiment
from tweets_simple;