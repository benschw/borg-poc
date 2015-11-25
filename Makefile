dl:
	wget https://drone.io/github.com/benschw/chinchilla/files/chinchilla.gz
	gunzip chinchilla.gz
	chmod 755 chinchilla

chinchilla:
	./chinchilla -config ./chinchilla.yaml

.PHONY: chinchilla
