include joomla.mk

library_source := $(shell find src/Org -type f)

phpdoc_bin := vendor/bin/phpdoc
phpdoc_build := build/phpdoc
phpdoc_target := build/phpdoc/index.html

.PHONY: all doc clean

all: joomla

doc: $(phpdoc_target)

clean:
	@rm -rf build

$(phpdoc_target): $(phpdoc_bin) $(library_source)
	@php $(phpdoc_bin) run -d src/Org -t $(phpdoc_build) \
		--visibility public --template clean

$(phpdoc_bin): composer.json
	@composer update