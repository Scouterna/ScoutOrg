SOURCE = ./src
SOURCEFILES = $(wildcard $(SOURCE)/*)

BUILD = ./build
BUILDFILES = $(wildcard $(BUILD)/*)

LIB = $(SOURCE)/Org
LIBFILES = $(wildcard $(LIB)/*)

JOOMLA = $(SOURCE)/joomla
JOOMLAFILES = $(wildcard $(JOOMLA)/*)

DOC = $(BUILD)/phpdoc
DOCFILES = $(wildcard $(DOC)/*)

joomla: $(LIBFILES) $(JOOMLAFILES)
	@rm -rf $(BUILD)/tmp
	@mkdir $(BUILD)/tmp
	@cp -lr $(LIB) $(BUILD)/tmp
	@cp -lr $(JOOMLA)/* $(BUILD)/tmp
	@cd $(BUILD)/tmp && zip -FSr ../JScoutOrg.zip ./
	@rm -rf $(BUILD)/tmp

doc: $(LIBFILES)
	@php phpDocumentor.phar run -d $(LIB) -t $(DOC) --visibility public --template clean

clean: $(BUILDFILES)
	@rm -rf $(BUILDFILES)