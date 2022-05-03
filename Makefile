
.PHONY: run
run:
	php ./bin/seeder.php seeder:fill-data --templates-dir=$(PWD)/notations/fake --params=$(PWD)/.env

.PHONY: test
test:
	composer test
