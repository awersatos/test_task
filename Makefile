build:
	docker-compose build --force-rm --compress

start:
	docker-compose up -d --remove-orphans --no-recreate

stop:
	docker-compose stop

down:
	docker-compose down --remove-orphans

ps:
	docker-compose ps
