
.PHONY: run
run:
	php ./bin/seeder.php seeder:fill-data --templates-dir=$(PWD)/examples --mode=predefined --params=$(PWD)/.env

.PHONY: test
test:
	composer test
