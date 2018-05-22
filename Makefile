SOURCE = ./src
SOURCEFILES = $(wildcard $(SOURCE)/*.php)
TARGET = ./phpdoc
TARGETFILES = $(wildcard $(TARGET)/*)
DOCS = $(wildcard ./docs/*)

all: $(TARGETFILES) $(DOCS)
	@php phpDocumentor.phar run -d $(SOURCE) -t $(TARGET) --visibility public --template clean

clean:
	@rm -rf $(TARGETFILES)