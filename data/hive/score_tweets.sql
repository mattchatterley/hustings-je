
ADD JAR jsonserde.jar;

-- load data into Hive
LOAD DATA INPATH '/user/flume/tweets/test1/' OVERWRITE INTO TABLE tweets_raw;

-- create results table ready for export
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
FROM tweets_simple t LEFT OUTER JOIN tweets_sentiment s on t.id = s.id;