start:
	docker compose up -d
stop:
	docker compose down
migration:
	docker compose exec php bin/console make:migration
migrate:
	docker compose exec php bin/console doctrine:migrations:migrate
form:
	docker compose exec php bin/console make:form