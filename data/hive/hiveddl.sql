ADD JAR jsonserde.jar;
--ADD JAR hive-json-serde-0.2.jar;

-- DROP tables as they exist already
DROP TABLE tweets_raw;
DROP TABLE tweets_raw;
DROP TABLE dictionary;
DROP TABLE time_zone_map;
DROP VIEW tweets_simple;
DROP VIEW tweets_clean;
DROP VIEW l1;
DROP VIEW l2;
DROP VIEW l3;
DROP TABLE tweets_sentiment;
DROP TABLE tweetsbi;


--create the tweets_raw table containing the records as received from Twitter

CREATE EXTERNAL TABLE tweets_raw (
   id BIGINT,
   created_at STRING,
   --source STRING,
   --favorited BOOLEAN,
   --retweet_count INT,
--   retweeted_status STRUCT<
--      text:STRING,
--      user:STRUCT<screen_name:STRING,name:STRING>>,
   --entities STRUCT<
   --   urls:ARRAY<STRUCT<expanded_url:STRING>>,
   --   user_mentions:ARRAY<STRUCT<screen_name:STRING,name:STRING>>,
   --   hashtags:ARRAY<STRUCT<text:STRING>>>,
   text STRING,
   screen_name STRING,
   name STRING
      --friends_count:INT,
      --followers_count:INT,
      --statuses_count:INT,
      --verified:BOOLEAN,
      --utc_offset:STRING, -- was INT but nulls are strings
      --time_zone:STRING
	  
   --in_reply_to_screen_name STRING
   --year int,
   --month int,
   --day int,
   --hour int
)
ROW FORMAT SERDE 'com.amazon.elasticmapreduce.JsonSerde'
WITH SERDEPROPERTIES ( 
      'paths'='id, created_at, text, user.screen_name, user.name'
	  )
LOCATION '/user/flume/tweets/test1/'
;

-- create sentiment dictionary
CREATE EXTERNAL TABLE dictionary (
    type string,
    length int,
    word string,
    pos string,
    stemmed string,
    polarity string
)
ROW FORMAT DELIMITED FIELDS TERMINATED BY '\t' 
STORED AS TEXTFILE
LOCATION '/user/root/dictionary';

--CREATE EXTERNAL TABLE time_zone_map (
--    time_zone string,
--    country string,
--    notes string
--)
--ROW FORMAT DELIMITED FIELDS TERMINATED BY '\t' 
--STORED AS TEXTFILE
--LOCATION '/user/time_zone_map/time_zone_map';

-- Clean up tweets
CREATE VIEW tweets_simple AS
SELECT
  id,
  cast ( from_unixtime( unix_timestamp(concat( '2014 ', substring(created_at,5,15)), 'yyyy MMM dd hh:mm:ss')) as timestamp) ts,
  translate(text, '\r', ' ') as text,
  name,
  screen_name
FROM tweets_raw
;

CREATE VIEW tweets_clean AS
SELECT
  id,
  ts,
  text,
  screen_name,
  name
 FROM tweets_simple t --LEFT OUTER JOIN time_zone_map m ON t.time_zone = m.time_zone;

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
 
 create table tweets_sentiment stored as orc as select 
  id, 
  case 
    when sum( polarity ) > 0 then 'positive' 
    when sum( polarity ) < 0 then 'negative'  
    else 'neutral' end as sentiment 
 from l3 group by id;

-- put everything back together and re-number sentiment
CREATE EXTERNAL TABLE tweetsbi (
	id string,
	name string,
	screen_name string,
	sentiment string,
	sentimentscore int,
	text string,
	ts timestamp
)
ROW FORMAT DELIMITED FIELDS TERMINATED BY '\t'
STORED AS TEXTFILE;

INSERT OVERWRITE TABLE tweetsbi
SELECT 
  t.id,
  t.name,
  t.screen_name,
  --'', 0,
  s.sentiment,
  case s.sentiment 
    when 'positive' then 2 
    when 'neutral' then 1 
    when 'negative' then 0 
  end as sentimentScore,
  t.text,
  t.ts
FROM tweets_clean t LEFT OUTER JOIN tweets_sentiment s on t.id = s.id;

--CREATE TABLE tweetsbi 
--STORED AS ORC
--AS
--SELECT 
--  t.*,
--  case s.sentiment 
--    when 'positive' then 2 
--    when 'neutral' then 1 
--    when 'negative' then 0 
--  end as sentimentScore,
--  s.sentiment  
--FROM tweets_clean t LEFT OUTER JOIN tweets_sentiment s on t.id = s.id;

-- for Tableau or Excel
-- UDAF sentiscore = sum(sentiment)*50  / count(sentiment)

-- context n-gram made readable
--CREATE TABLE twitter_3grams
--STORED AS RCFilese
--AS
--SELECT year, month, day, hour, snippet 
--FROM
--( SELECT
--    year,
--    month,
--     day,
--     hour,
--     context_ngrams(sentences(lower(text)), array("iron","man","3",null,null,null), 10) ngs
--  FROM tweets group by year,month,day, hour 
--) base
-- LATERAL VIEW
--     explode(  ngs  ) ngsTab AS snippet -- ngsTab is random alias => must be there even though not used
--; 