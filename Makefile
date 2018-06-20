lib_source := $(shell find src/Org -type f)
joomla_source := $(shell find src/joomla -type f)

joomla_targetname := JScoutOrg.zip
joomla_target := build/$(joomla_targetname)

.PHONY: all joomla doc clean

all: joomla

joomla: $(joomla_target)

doc:
	@php phpDocumentor.phar run -d src/Org -t build/phpdoc \
		--visibility public --template clean

clean:
	@rm -r build

$(joomla_target): $(joomla_source) $(lib_source)
	@rm -rf build/tmp
	@mkdir -p build/tmp
	@cp -lr src/Org build/tmp
	@cp -lr src/joomla/* build/tmp
	@cd build/tmp && zip -FSr ../$(joomla_targetname) ./
	@rm -r build/tmp
	