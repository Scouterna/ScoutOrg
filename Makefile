lib_source := $(shell find src/Org -type f)
joomla_source := $(shell find src/joomla -type f)

joomla_targetname := JScoutOrg.zip
joomla_target := build/joomla/$(joomla_targetname)

.PHONY: all joomla doc clean

all: joomlas

joomla: $(joomla_target)

doc:
	@php phpDocumentor.phar run -d src/Org -t build/phpdoc \
		--visibility public --template clean

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