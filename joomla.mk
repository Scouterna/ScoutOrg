joomla_plugin_source := $(shell find src/joomla/plugin -type f)
joomla_library_source := $(shell find src/joomla/library -type f)
joomla_component_source := $(shell find src/joomla/component -type f)
joomla_package_source := $(shell find src/joomla/package -type f)
library_source := $(shell find src/Org -type f)

joomla_plugin_name := plg_system_scoutorg.zip
joomla_library_name := lib_scoutorg.zip
joomla_component_name := com_scoutorg.zip
joomla_package_name := scoutorg.zip

joomla_plugin_tmp := build/joomla/tmp/plugin
joomla_library_tmp := build/joomla/tmp/library
joomla_component_tmp := build/joomla/tmp/component
joomla_package_tmp := build/joomla/tmp/package

joomla_plugin_target := build/joomla/tmp/$(joomla_plugin_name)
joomla_library_target := build/joomla/tmp/$(joomla_library_name)
joomla_component_target := build/joomla/tmp/$(joomla_component_name)
joomla_package_target := build/joomla/$(joomla_package_name)

current_date := $(shell date +'%Y-%m-%d')

replace_text = sed -i 's/\[\[$(1)\]\]/$(2)/g' $(3)

.PHONY: joomla

joomla: $(joomla_package_target)

$(joomla_package_target): $(joomla_plugin_target) $(joomla_library_target) $(joomla_component_target) $(joomla_package_source)
	@echo Creating joomla package
	@rm -rf $(joomla_package_tmp)
	@mkdir -p $(joomla_package_tmp)
	@mkdir $(joomla_package_tmp)/packages
	@cp $(joomla_library_target) $(joomla_package_tmp)/packages
	@cp $(joomla_plugin_target) $(joomla_package_tmp)/packages
	@cp $(joomla_component_target) $(joomla_package_tmp)/packages
	@cp -r src/joomla/package/* $(joomla_package_tmp)
	@$(call replace_text,current_date,$(current_date),$(joomla_package_tmp)/pkg_scoutorg.xml)
	@$(call replace_text,plugin_filename,$(joomla_plugin_name),$(joomla_package_tmp)/pkg_scoutorg.xml)
	@$(call replace_text,library_filename,$(joomla_library_name),$(joomla_package_tmp)/pkg_scoutorg.xml)
	@$(call replace_text,component_filename,$(joomla_component_name),$(joomla_package_tmp)/pkg_scoutorg.xml)
	@cd $(joomla_package_tmp) && \
		zip -FSr ../../$(joomla_package_name) ./

$(joomla_component_target): $(joomla_component_source)
	@echo Creating joomla component
	@rm -rf $(joomla_component_tmp)
	@mkdir -p $(joomla_component_tmp)
	@cp -r src/joomla/component/* $(joomla_component_tmp)
	@$(call replace_text,current_date,$(current_date),$(joomla_component_tmp)/scoutorg.xml)
	@cd $(joomla_component_tmp) && \
		zip -FSr ../$(joomla_component_name) ./

$(joomla_plugin_target): $(joomla_plugin_source)
	@echo Creating joomla plugin
	@rm -rf $(joomla_plugin_tmp)
	@mkdir -p $(joomla_plugin_tmp)
	@cp -r src/joomla/plugin/* $(joomla_plugin_tmp)
	@$(call replace_text,current_date,$(current_date),$(joomla_plugin_tmp)/scoutorg.xml)
	@cd $(joomla_plugin_tmp) && \
		zip -FSr ../$(joomla_plugin_name) ./

$(joomla_library_target): $(joomla_library_source) $(library_source)
	@echo Creating joomla library
	@rm -rf $(joomla_library_tmp)
	@mkdir -p $(joomla_library_tmp)
	@cp -r src/Org $(joomla_library_tmp)
	@cp -r src/joomla/library/* $(joomla_library_tmp)
	@$(call replace_text,current_date,$(current_date),$(joomla_library_tmp)/scoutorg.xml)
	@cd $(joomla_library_tmp) && \
		zip -FSr ../$(joomla_library_name) ./