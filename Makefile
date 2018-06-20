SOURCE = ./src/Org
SOURCEFILES = $(wildcard $(SOURCE)/*.php)
DOC = ./phpdoc
DOCFILES = $(wildcard $(DOC)/*)
DOCS = $(wildcard ./docs/*)
ZIP = ./build/ScoutOrg.zip

zip: $(SOURCE)
	@zip -r $(ZIP) $(SOURCE)

doc: $(DOCFILES) $(DOCS)
	@php phpDocumentor.phar run -d $(SOURCE) -t $(DOC) --visibility public --template clean

clean:
	@rm -rf $(DOCFILES)
	@rm $(ZIP)