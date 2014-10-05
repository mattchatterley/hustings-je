
ADD JAR jsonserde.jar;

-- load data into Hive
--LOAD DATA INPATH '/user/root/clean/' OVERWRITE INTO TABLE tweets_raw;
--LOAD DATA INPATH '/user/flume/tweets/test1/' OVERWRITE INTO TABLE tweets_raw;
LOAD DATA INPATH '/user/root/clean' OVERWRITE INTO TABLE tweets_scrubbed;

INSERT OVERWRITE TABLE tweets_raw 
SELECT get_json_object(tweet, '$.id')
	, get_json_object(tweet, '$.created_at')
	, translate(translate(translate(get_json_object(tweet, '$.text'), '\r', ' '), '\n', ' '), '\t', ' ')
	, translate(translate(translate(get_json_object(tweet, '$.user.screen_name'), '\n', ' '), 't', ' '), '\r', ' ')
	, translate(translate(translate(get_json_object(tweet, '$.user.name'), '\n', ' '), '\t', ' '), '\r', ' ') 
FROM tweets_scrubbed 
WHERE length(tweet) > 0;

-- create results table ready for export
INSERT OVERWRITE TABLE tweetsbi
SELECT 
  t.id,
  t.name,
  t.screen_name,
  --'', 0,
  s.sentiment,
  case s.sentiment 
    when 'positive' then 1 
    when 'neutral' then 0 
    when 'negative' then -1 
  end as sentimentScore,
  t.text,
  t.ts,
  n.NlpSentiment,
  case n.NlpSentiment
    when 'VERY_NEGATIVE' then -2
    when 'NEGATIVE' then -1
    when 'NEUTRAL' then 0
    when 'POSITIVE' then 1
    when 'VERY_POSITIVE' then 2
  end as NlpSentimentScore
FROM tweets_simple t 
LEFT OUTER JOIN tweets_sentiment s on t.id = s.id
LEFT OUTER JOIN nlp_score n on n.id = t.id;

