include joomla.mk

library_source_dev := $(shell find src/Org/Lib -type f)
library_source_indev := $(shell find src/Org -type f)

phpdoc_build_exdev := build/doc_exdev
phpdoc_target_exdev := $(phpdoc_build_exdev)/index.html

phpdoc_build_indev := build/doc_indev
phpdoc_target_indev := $(phpdoc_build_indev)/index.html

.PHONY: all doc clean

all: joomla

doc_exdev: $(phpdoc_target_exdev)

doc_indev: $(phpdoc_target_indev)

clean:
	@rm -rf build

$(phpdoc_target_exdev): $(library_source_exdev)
	@phpdoc run -d src/Org/Lib -t $(phpdoc_build_exdev) \
		--visibility public --template clean

$(phpdoc_target_indev): $(library_source_indev)
	@phpdoc run -d src/Org -t $(phpdoc_build_indev) \
		--template clean