#### starting

download kafka

```
wget http://mirror.nbtelecom.com.br/apache/kafka/2.4.0/kafka_2.12-2.4.0.tgz
tar -xzf kafka_2.12-2.4.0.tgz
cd kafka_2.12-2.4.0
```

zookeeper

`./bin/zookeeper-server-start.sh config/zookeeper.properties`

kafka

`./bin/kafka-server-start.sh ./config/server.properties`


#### libs

* https://github.com/nmred/kafka-php
* https://github.com/quipo/kafka-php
