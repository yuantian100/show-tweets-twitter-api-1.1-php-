<h1>Overview</h1>

Use abraham's <a href="https://github.com/abraham/twitteroauth">PHP library for working with Twitter's OAuth API</a>

<h2>Usage</h2>

There are four important files: <br>

<b>twitter/OAuth.php</b> and <b>twitter/twitteroauth.php</b> : twitter api library provided by abraham. ( you don't need to change any code here)

<b>twitter/tweets.php</b> (include all your twitter settings, CONSUMER_KEY, CONSUMER_SECRET...), change it use yours

<b>index.php</b>: some basic html code to show the tweets result.

<h2>Cache data</h2>

The 'tmp' folder is used to cache your twitter data. You can change cache path and time settings at <b>twitter/tweets.php</b>


