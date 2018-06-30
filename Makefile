lib_source := $(shell find src/Org -type f)
joomla_source := $(shell find src/joomla -type f)

joomla_targetname := JScoutOrg.zip
joomla_target := build/joomla/$(joomla_targetname)

phpdoc_bin := vendor/bin/phpdoc
phpdoc_build := build/phpdoc
phpdoc_target := build/phpdoc/index.html

.PHONY: all joomla doc clean

all: joomla

joomla: $(joomla_target)

doc: $(phpdoc_target)

clean:
	@rm -r build

$(joomla_target): $(joomla_source) $(lib_source)
	@rm -rf build/tmp
	@mkdir -p build/tmp
	@cp -r src/Org build/tmp
	@cp -r src/joomla/* build/tmp
	@mkdir -p build/joomla
	@cd build/tmp && zip -FSr ../joomla/$(joomla_targetname) ./
	@rm -rf build/tmp

$(phpdoc_target): $(phpdoc_bin) $(lib_source)
	@php $(phpdoc_bin) run -d src/Org -t $(phpdoc_build) \
		--visibility public --template clean

$(phpdoc_bin): composer.json
	@composer update