ADD JAR jsonserde.jar;

DROP TABLE tweets_raw_test;
DROP TABLE tweets_raw;
DROP TABLE tweetsbi;
--create the tweets_raw table containing the records as received from Twitter

CREATE EXTERNAL TABLE tweets_raw (
   id BIGINT,
   created_at STRING,
   text STRING,
   screen_name STRING,
   name STRING
)
ROW FORMAT SERDE 'com.amazon.elasticmapreduce.JsonSerde'
WITH SERDEPROPERTIES ( 
      'paths'='id, created_at, text, user.screen_name, user.name'
	  );

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