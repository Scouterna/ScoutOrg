include joomla.mk

library_source := $(shell find src/Org -type f)

phpdoc_build := build/phpdoc
phpdoc_target := build/phpdoc/index.html

.PHONY: all doc clean

all: joomla

doc: $(phpdoc_target)

clean:
	@rm -rf build

$(phpdoc_target): $(library_source)
	@phpdoc run -d src/Org -t $(phpdoc_build) \
		--visibility public --template clean