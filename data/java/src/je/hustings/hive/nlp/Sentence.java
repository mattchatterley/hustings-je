package je.hustings.hive.nlp;

import java.util.EnumMap;

public class Sentence {
    private EnumMap<SentimentClass, Double> sentimentProbabilities;
    private String sentence;
    private SentimentClass sentimentClass;

    public Sentence( double[] sentimentProbabilities
                   , String sentence
                   , SentimentClass sentimentClass
                   )
    {
        this.sentence = sentence;
        this.sentimentClass = sentimentClass;
        this.sentimentProbabilities = new EnumMap<SentimentClass, Double>(SentimentClass.class);
        for(int i = 0;i < sentimentProbabilities.length;++i)
        {
            this.sentimentProbabilities.put(SentimentClass.values()[i], sentimentProbabilities[i]);
        }
    }

    public String getSentence() { return sentence;}
    public EnumMap<SentimentClass, Double> getSentimentProbabilities() { return sentimentProbabilities;}
    public SentimentClass getSentiment() { return sentimentClass;}

}
