lib_source := $(wildcard src/Org/*.php)
joomla_source := $(wildcard src/joomla/*)

joomla_targetname = JScoutOrg.zip
joomla_target := build/$(joomla_targetname)

phpdoc_targetname = phpdoc
phpdoc_target := build/$(phpdoc_targetname)

.PHONY: all joomla doc clean

all: $(joomla_target) $(phpdoc_target)

joomla: $(joomla_target)

doc: $(phpdoc_target)

clean:
	@rm -r build

$(joomla_target): $(joomla_source) $(lib_source)
	@rm -rf build/tmp
	@mkdir -p build/tmp
	@cp -lr src/Org build/tmp
	@cp -lr src/joomla/* build/tmp
	@cd build/tmp && zip -FSr ../$(joomla_targetname) ./
	@rm -r build/tmp

$(phpdoc_target): $(lib_source)
	@php phpDocumentor.phar run -d src/Org -t $(phpdoc_target) --visibility public --template clean