(()=>{var e={BU7s:(e,t,n)=>{var i={"./ContentChildElementFormScope":"UHZ2","./ContentChildElementFormScope.vue":"UHZ2","./LinkedReviewAdminEdit":"rZyl","./LinkedReviewAdminEdit.vue":"rZyl","./LinkedReviewAdminSummary":"BfV2","./LinkedReviewAdminSummary.vue":"BfV2","./LinkedReviewAdminView":"W18G","./LinkedReviewAdminView.vue":"W18G","./LinkedReviewParticipantForm":"4Ip8","./LinkedReviewParticipantForm.vue":"4Ip8","./LinkedReviewParticipantPrint":"r1e8","./LinkedReviewParticipantPrint.vue":"r1e8","./SelectContent":"r3CX","./SelectContent.vue":"r3CX","./SelectParticipant":"bBKE","./SelectParticipant.vue":"bBKE"};function s(e){var t=r(e);return n(t)}function r(e){if(!n.o(i,e)){var t=new Error("Cannot find module '"+e+"'");throw t.code="MODULE_NOT_FOUND",t}return i[e]}s.keys=function(){return Object.keys(i)},s.resolve=r,e.exports=s,s.id="BU7s"},UHZ2:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>l});var i=n("U9m6"),s=n.n(i);const r=tui.require("mod_perform/components/element/ChildElementFormScope"),a={components:{ChildElementFormScope:n.n(r)(),FormScope:s()},props:{childElement:Object,contentItem:Object,path:[Array,String],sectionElement:Object},computed:{childElementResponsesIdentifier(){return this.sectionElement.element.element_plugin.child_element_config.child_element_responses_identifier}},methods:{contentPath(){let e=[],t=this.sectionElement.element.element_plugin.child_element_config.repeating_item_identifier;return this.path instanceof String&&e.push(this.path),this.path instanceof Array&&this.path.forEach((t=>e.push(t))),e.push("response",t),e},normalizeContentResponses(e){if(Array.isArray(e)){let t={};return e.map((e=>{t[e.content_id]=e})),t}return e},contentResponsesProcessor(e){if(Array.isArray(e[this.childElementResponsesIdentifier])){let t={};e[this.childElementResponsesIdentifier].map((e=>{t[e.child_element_id]=e})),e[this.childElementResponsesIdentifier]=t}return e.content_id||(e.content_id=this.contentItem.id),e[this.childElementResponsesIdentifier]=this.removeInvalidChildResponses(e[this.childElementResponsesIdentifier]),e},removeInvalidChildResponses(e){return Object.keys(e).filter((t=>{let n=e[t];return this.withoutChildElementId(n)})).map((t=>{delete e[t]})),e},withoutChildElementId:e=>!e.child_element_id}};var o=(0,n("wWJ2").Z)(a,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("FormScope",{attrs:{path:e.contentPath(),process:e.normalizeContentResponses}},[n("FormScope",{attrs:{path:e.contentItem.id,process:e.contentResponsesProcessor}},[n("ChildElementFormScope",{key:e.childElement.id,attrs:{element:e.childElement,"child-element-responses-identifier":e.childElementResponsesIdentifier}},[e._t("default")],2)],1)],1)}),[],!1,null,null,null);o.options.__hasBlocks={script:!0,template:!0};const l=o.exports},rZyl:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>f});const i=tui.require("tui/components/form/Checkbox");var s=n.n(i),r=n("U9m6"),a=n.n(r),o=n("p202");const l=tui.require("mod_perform/components/element/PerformAdminCustomElementEdit");var d=n.n(l),c=n("Y2he"),m=n.n(c);const p=tui.require("performelement_linked_review/components/SelectParticipant");var u=n.n(p);const _={components:{Checkbox:s(),FormCheckboxGroup:o.FormCheckboxGroup,FormRadioGroup:o.FormRadioGroup,FormRow:o.FormRow,FormScope:a(),FormSelect:o.FormSelect,PerformAdminCustomElementEdit:d(),Radio:m(),SelectParticipant:u()},inheritAttrs:!1,props:{identifier:String,isRequired:Boolean,rawData:Object,rawTitle:String,settings:Object,section:Object,sectionId:[Number,String]},data(){return{contentTypes:[{id:null,label:this.$str("select_type","performelement_linked_review")}],initialValues:Object.assign({content_type:null,content_type_settings:{}},this.rawData,{rawTitle:this.rawTitle,identifier:this.identifier,responseRequired:this.isRequired}),selectedTypeId:this.rawData.content_type||null,selectionRelationships:this.rawData.selection_relationships||[]}},apollo:{contentTypes:{query:{kind:"Document",definitions:[{kind:"OperationDefinition",operation:"query",name:{kind:"Name",value:"performelement_linked_review_content_types"},variableDefinitions:[{kind:"VariableDefinition",variable:{kind:"Variable",name:{kind:"Name",value:"section_id"}},type:{kind:"NonNullType",type:{kind:"NamedType",name:{kind:"Name",value:"core_id"}}},directives:[]}],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",alias:{kind:"Name",value:"types"},name:{kind:"Name",value:"performelement_linked_review_content_types"},arguments:[{kind:"Argument",name:{kind:"Name",value:"section_id"},value:{kind:"Variable",name:{kind:"Name",value:"section_id"}}}],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",alias:{kind:"Name",value:"id"},name:{kind:"Name",value:"identifier"},arguments:[],directives:[]},{kind:"Field",alias:{kind:"Name",value:"label"},name:{kind:"Name",value:"display_name"},arguments:[],directives:[]},{kind:"Field",name:{kind:"Name",value:"available_settings"},arguments:[],directives:[]},{kind:"Field",name:{kind:"Name",value:"admin_settings_component"},arguments:[],directives:[]}]}}]}}]},variables(){return{section_id:this.sectionId}},update({types:e}){return e.length<2?e:[{id:null,label:this.$str("select_type","performelement_linked_review")}].concat(e)}}},computed:{coreRelationships(){return this.section.section_relationships.map((e=>e.core_relationship)).filter((({idnumber:e})=>"perform_external"!=e))},hasExistingType(){return!(!this.rawData||!this.rawData.content_type)},selectedContentTypeSettingsComponent(){return this.$apollo.loading||null==this.selectedType||null==this.selectedType.admin_settings_component?null:tui.asyncComponent(this.selectedType.admin_settings_component)},selectedType(){return this.selectedTypeId||1!==this.contentTypes.length?this.contentTypes.find((e=>e.id===this.selectedTypeId)):this.contentTypes[0]}},methods:{handleSubmit(e){Array.isArray(e.data.selection_relationships)||(e.data.selection_relationships=[e.data.selection_relationships]);let t=Object.assign({},e);this.$emit("update",t)},updateForm(e){const t=e.content_type;if(this.selectedTypeId===t)return;this.selectedTypeId=e.content_type;let n={};null!=this.selectedTypeId&&(n=JSON.parse(this.selectedType.available_settings)),this.$refs.form.$refs.form.update("content_type_settings",n)}}},h=function(e){e.options.__langStrings={performelement_linked_review:["selection_participant","review_type","participant_selection_help","select_type"]}};var v=(0,n("wWJ2").Z)(_,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"tui-linkedReviewAdminEdit"},[n("PerformAdminCustomElementEdit",{ref:"form",attrs:{"initial-values":e.initialValues,settings:e.settings},on:{cancel:function(t){return e.$emit("display")},change:e.updateForm,update:e.handleSubmit}},[n("FormRow",{attrs:{label:e.$str("review_type","performelement_linked_review"),required:!0}},[n("FormSelect",{attrs:{id:e.$id("select-content-type"),"char-length":"20",disabled:e.hasExistingType,name:"content_type",options:e.contentTypes,validations:function(e){return[e.required()]}}})],1),e._v(" "),n("SelectParticipant",{attrs:{"field-name":"selection_relationships","help-msg":e.$str("participant_selection_help","performelement_linked_review"),label:e.$str("selection_participant","performelement_linked_review"),relationships:e.coreRelationships}}),e._v(" "),e.selectedContentTypeSettingsComponent?n("FormScope",{attrs:{path:"content_type_settings"}},[n(e.selectedContentTypeSettingsComponent,{ref:"typeSettings",tag:"component",attrs:{relationships:e.coreRelationships}})],1):e._e()],1)],1)}),[],!1,null,null,null);h(v),v.options.__hasBlocks={script:!0,template:!0};const f=v.exports},BfV2:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>o});const i=tui.require("mod_perform/components/element/PerformAdminCustomElementSummary"),s={components:{PerformAdminCustomElementSummary:n.n(i)()},inheritAttrs:!1,props:{data:Object,identifier:String,isRequired:Boolean,settings:Object,title:String},computed:{extraFields(){let e=[{title:this.$str("review_type","performelement_linked_review"),value:this.data.content_type_display},{title:this.$str("selection_participant","performelement_linked_review"),value:this.data.selection_relationships_display[0].name}];return this.data.content_type_settings_display&&this.data.content_type_settings_display.forEach((t=>{e.push({title:t.title,value:t.value})})),e}}},r=function(e){e.options.__langStrings={performelement_linked_review:["selection_participant","review_type"]}};var a=(0,n("wWJ2").Z)(s,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"tui-linkedReviewAdminSummary"},[n("PerformAdminCustomElementSummary",{attrs:{"extra-fields":e.extraFields,identifier:e.identifier,"is-required":e.isRequired,settings:e.settings,title:e.title},on:{display:function(t){return e.$emit("display")}}})],1)}),[],!1,null,null,null);r(a),a.options.__hasBlocks={script:!0,template:!0};const o=a.exports},W18G:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>d});var i=n("ifSE"),s=n.n(i);const r=tui.require("mod_perform/components/element/PerformAdminChildElements");var a=n.n(r);const o={components:{Card:s(),PerformAdminChildElements:a()},inheritAttrs:!1,props:{activityContextId:Number,activityId:Number,activityState:Object,data:Object,elementId:[Number,String],elementPlugins:Array,reportPreview:Boolean,sectionElement:Object,sectionId:Number},computed:{compatibleElementPlugins(){let e=this.sectionElement.element.data.compatible_child_element_plugins;return this.reportPreview||this.activityState.code||!e?[]:this.elementPlugins.filter((t=>e.includes(t.plugin_name)))}},methods:{getFooterComponent(){return this.data.components&&this.data.components.admin_content_footer?tui.asyncComponent(this.data.components.admin_content_footer):null},getTypeComponent(){return this.sectionElement.element.data.components?tui.asyncComponent(this.sectionElement.element.data.components.admin_view):null}}};var l=(0,n("wWJ2").Z)(o,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"tui-linkedReviewAdminView"},[n("Card",{staticClass:"tui-linkedReviewAdminView__card",attrs:{"no-border":!0}},[n(e.getTypeComponent(),{tag:"Component",attrs:{data:e.data}})],1),e._v(" "),n("div",{staticClass:"tui-linkedReviewAdminView__questions"},[e.reportPreview?e._e():n("PerformAdminChildElements",{attrs:{"activity-context-id":e.activityContextId,"activity-id":e.activityId,"activity-state":e.activityState,"addable-element-plugins":e.compatibleElementPlugins,"element-id":e.elementId,"section-element":e.sectionElement,"section-id":e.sectionId},on:{"child-update":function(t){return e.$emit("child-update",t)},"unsaved-child":function(t){return e.$emit("unsaved-child",t)}}})],1),e._v(" "),n(e.getFooterComponent(),{tag:"component",staticClass:"tui-linkedReviewAdminView__footer",attrs:{settings:e.data.content_type_settings}})],1)}),[],!1,null,null,null);l.options.__hasBlocks={script:!0,template:!0};const d=l.exports},"4Ip8":(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>v});var i=n("ifSE"),s=n.n(i);const r=tui.require("tui/components/loading/Loader");var a=n.n(r);const o=tui.require("mod_perform/components/element/ElementParticipantResponseHeader");var l=n.n(o);const d={kind:"Document",definitions:[{kind:"OperationDefinition",operation:"query",name:{kind:"Name",value:"performelement_linked_review_content_items"},variableDefinitions:[{kind:"VariableDefinition",variable:{kind:"Variable",name:{kind:"Name",value:"input"}},type:{kind:"NonNullType",type:{kind:"NamedType",name:{kind:"Name",value:"performelement_linked_review_content_items_input"}}},directives:[]}],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",name:{kind:"Name",value:"performelement_linked_review_content_items"},arguments:[{kind:"Argument",name:{kind:"Name",value:"input"},value:{kind:"Variable",name:{kind:"Name",value:"input"}}}],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",name:{kind:"Name",value:"items"},arguments:[],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",name:{kind:"Name",value:"id"},arguments:[],directives:[]},{kind:"Field",name:{kind:"Name",value:"content_id"},arguments:[],directives:[]},{kind:"Field",name:{kind:"Name",value:"selector"},arguments:[],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",name:{kind:"Name",value:"id"},arguments:[],directives:[]},{kind:"Field",name:{kind:"Name",value:"fullname"},arguments:[],directives:[]}]}},{kind:"Field",name:{kind:"Name",value:"content"},arguments:[],directives:[]},{kind:"Field",name:{kind:"Name",value:"meta_data"},arguments:[],directives:[]},{kind:"Field",alias:{kind:"Name",value:"created_at_date"},name:{kind:"Name",value:"created_at"},arguments:[{kind:"Argument",name:{kind:"Name",value:"format"},value:{kind:"EnumValue",value:"DATELONG"}}],directives:[]}]}}]}}]}}]},c={kind:"Document",definitions:[{kind:"OperationDefinition",operation:"query",name:{kind:"Name",value:"performelement_linked_review_content_items_nosession"},variableDefinitions:[{kind:"VariableDefinition",variable:{kind:"Variable",name:{kind:"Name",value:"input"}},type:{kind:"NonNullType",type:{kind:"NamedType",name:{kind:"Name",value:"performelement_linked_review_content_items_input"}}},directives:[]}],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",name:{kind:"Name",value:"performelement_linked_review_content_items"},arguments:[{kind:"Argument",name:{kind:"Name",value:"input"},value:{kind:"Variable",name:{kind:"Name",value:"input"}}}],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",name:{kind:"Name",value:"items"},arguments:[],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",name:{kind:"Name",value:"id"},arguments:[],directives:[]},{kind:"Field",name:{kind:"Name",value:"content_id"},arguments:[],directives:[]},{kind:"Field",name:{kind:"Name",value:"content"},arguments:[],directives:[]},{kind:"Field",alias:{kind:"Name",value:"created_at_date"},name:{kind:"Name",value:"created_at"},arguments:[{kind:"Argument",name:{kind:"Name",value:"format"},value:{kind:"EnumValue",value:"DATELONG"}}],directives:[]}]}}]}}]}}]},m=tui.require("performelement_linked_review/components/ContentChildElementFormScope");var p=n.n(m);const u={components:{Card:s(),ContentChildElementFormScope:p(),Loader:a(),ResponseHeader:l()},props:{activeSectionIsClosed:Boolean,anonymousResponses:Boolean,checkboxGroupId:String,coreRelationshipId:[String,Number],element:Object,error:String,fromPrint:Boolean,hasPrintedToDoIcon:Boolean,isDraft:Boolean,isExternalParticipant:Boolean,participantCanAnswer:Boolean,participantInstanceId:{type:[String,Number],required:!1},path:{type:[String,Array],default:""},sectionElement:Object,showOtherResponse:Boolean,subjectUser:{required:!0,type:Object},subjectInstanceId:{type:Number,required:!0},token:String,viewOnly:Boolean,currentUserId:Number},data(){return{contentSettings:this.element.data.content_type_settings,groupId:this.$id("label"),selectedContent:[]}},computed:{userId(){return parseInt(this.subjectUser.id)},canSelectContent(){return this.coreRelationshipId==this.element.data.selection_relationships&&!this.fromPrint&&!this.isExternalParticipant},showSelectedBy(){return this.coreRelationshipId===this.element.data.selection_relationships[0]&&this.selectedContent.items[0]&&this.selectedContent.items[0].selector&&this.currentUserId!=this.selectedContent.items[0].selector.id},showItemNotSelected(){return this.viewOnly&&void 0===this.selectedContent.items[0]}},methods:{getContentComponent:e=>tui.asyncComponent(e.data.components.participant_content),getFooterComponent:e=>e.data.components.participant_content_footer?tui.asyncComponent(e.data.components.participant_content_footer):null,getFormComponent:e=>tui.asyncComponent(e.element_plugin.participant_form_component),getPickerComponent:e=>tui.asyncComponent(e.data.components.content_picker),sectionElementWithResponseGroups(e,t){return Object.assign({},this.sectionElement,{element:t,response_data:this.childElementResponseData(e,t.id),response_data_raw:this.childElementResponseDataRaw(e,t.id),response_data_formatted_lines:this.childElementResponseDataFormattedLines(e,t.id),other_responder_groups:this.childElementOtherResponderGroups(e,t.id)})},childElementResponseData(e,t){return this.sectionElement.response_data?this.getContentChildElementValue(this.sectionElement.response_data,e,t):null},childElementResponseDataRaw(e,t){return this.sectionElement.response_data_raw?this.getContentChildElementValue(this.sectionElement.response_data_raw,e,t):null},childElementResponseDataFormattedLines(e,t){if(this.sectionElement.response_data_formatted_lines.length<1)return[];let n=JSON.parse(this.sectionElement.response_data_formatted_lines[0]);return this.getContentChildElementValue(n,e,t)},childElementOtherResponderGroups(e,t){return this.sectionElement.other_responder_groups.map((n=>{let i=n.responses.map((n=>{let i=null;null!==n.response_data&&(i=this.getContentChildElementValue(JSON.parse(n.response_data),e,t));let s=[];if(n.response_data_formatted_lines.length>0){let i=JSON.parse(n.response_data_formatted_lines[0]);s=this.getContentChildElementValue(i,e,t)}return Object.assign({},n,{response_data:i,response_data_formatted_lines:s})}));return Object.assign({},n,{responses:i})}))},getContentChildElementValue(e,t,n){let i=this.sectionElement.element.element_plugin.child_element_config.repeating_item_identifier,s=this.sectionElement.element.element_plugin.child_element_config.child_element_responses_identifier,r=e[i][t][s][n];return r&&r.response_data?r.response_data:null},refetch(e){e&&this.$emit("show-banner",e),this.loading=!0,this.$apollo.queries.selectedContent.refetch().then((()=>{let e=this.$refs["selected-content-item"];e.length&&e[0].scrollIntoView()}))},getContent(e){let t=e?JSON.parse(e):{};return null!==t?t:{}}},apollo:{selectedContent:{query(){return this.isExternalParticipant?c:d},variables(){return{input:{subject_instance_id:this.subjectInstanceId,participant_section_id:this.element.participantSectionId?this.element.participantSectionId:null,section_element_id:this.sectionElement.id,token:this.token?this.token:null}}},update:({performelement_linked_review_content_items:e})=>e}}},_=function(e){e.options.__langStrings={mod_perform:["items_selected_by","no_items_selected"]}};var h=(0,n("wWJ2").Z)(u,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return e.$apollo.loading?n("Loader",{attrs:{loading:e.$apollo.loading}}):n("div",{staticClass:"tui-linkedReviewParticipantForm"},[e.selectedContent.items&&0===e.selectedContent.items.length&&e.participantInstanceId?n(e.getPickerComponent(e.element),{tag:"component",attrs:{"is-draft":e.isDraft,"is-external-participant":e.isExternalParticipant,"can-show-adder":e.canSelectContent,"core-relationship":e.element.data.selection_relationships_display,"participant-instance-id":e.participantInstanceId,"preview-component":e.getContentComponent(e.element),required:e.element.is_required,"section-element-id":e.sectionElement.id,settings:e.contentSettings,"subject-user":e.subjectUser,"user-id":e.userId,"content-type":e.element.data.content_type},on:{update:e.refetch,"unsaved-plugin-change":function(t){return e.$emit("unsaved-plugin-change",t)}}}):[n("div",{staticClass:"tui-linkedReviewParticipantForm__selectedBy"},[e.showSelectedBy?[e._v("\n        "+e._s(e.$str("items_selected_by","mod_perform",{date:e.selectedContent.items[0].created_at_date,user:e.selectedContent.items[0].selector.fullname}))+"\n      ")]:e.showItemNotSelected?[e._v("\n        "+e._s(e.$str("no_items_selected","mod_perform"))+"\n      ")]:e._e()],2),e._v(" "),n("div",{staticClass:"tui-linkedReviewParticipantForm__items"},e._l(e.selectedContent.items,(function(t){return n("div",{key:t.id,ref:"selected-content-item",refInFor:!0,staticClass:"tui-linkedReviewParticipantForm__item"},[n("Card",{staticClass:"tui-linkedReviewParticipantForm__item-card",attrs:{"no-border":!0}},[n("div",{staticClass:"tui-linkedReviewParticipantForm__item-cardContent"},[n(e.getContentComponent(e.element),{tag:"component",attrs:{content:e.getContent(t.content),"created-at":t.created_at_date,"from-print":e.fromPrint,"is-external-participant":e.isExternalParticipant,"participant-instance-id":e.participantInstanceId,"subject-user":e.subjectUser}})],1),e._v(" "),e.fromPrint?e._e():n("div",{staticClass:"tui-linkedReviewParticipantForm__item-cardActions"})]),e._v(" "),n("div",{staticClass:"tui-linkedReviewParticipantForm__questions"},e._l(e.element.children,(function(i){return n("div",{key:i.id},[i.title?n("ResponseHeader",{attrs:{id:e.$id("title"),"has-printed-to-do-icon":e.hasPrintedToDoIcon&&i.is_respondable,"is-respondable":i.is_respondable,required:i.is_required,"sub-element":!0,title:i.title}}):e._e(),e._v(" "),n("div",{staticClass:"tui-linkedReviewParticipantForm__questions-content"},[n("ContentChildElementFormScope",{attrs:{"content-item":t,path:e.path,"section-element":e.sectionElement,"child-element":i}},[n(e.getFormComponent(i),e._b({tag:"component",attrs:{element:i,"element-components":i.element_plugin,"participant-instance-id":e.participantInstanceId,"from-print":e.fromPrint,path:"response_data","section-element":e.sectionElementWithResponseGroups(t.id,i),"active-section-is-closed":e.activeSectionIsClosed,"anonymous-responses":e.anonymousResponses,error:e.error,"group-id":e.checkboxGroupId,"is-draft":e.isDraft,"is-external-participant":e.isExternalParticipant,"participant-can-answer":e.participantCanAnswer,"subject-instance-id":e.subjectInstanceId,"show-other-response":e.showOtherResponse,"view-only":e.viewOnly,token:e.token,"extra-data":{content:e.getContent(t.content)}}},"component",e.$attrs,!1))],1)],1)],1)})),0),e._v(" "),n(e.getFooterComponent(e.element),{tag:"component",attrs:{content:e.getContent(t.content),"element-data":e.element.data,"participant-instance-id":e.participantInstanceId,"section-element-id":e.sectionElement.id,"subject-user":e.subjectUser,"from-print":e.fromPrint},on:{"show-banner":function(t){return e.$emit("show-banner",t)}}})],1)})),0)]],2)}),[],!1,null,null,null);_(h),h.options.__hasBlocks={script:!0,template:!0};const v=h.exports},r1e8:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>a});const i=tui.require("tui/components/form/NotepadLines"),s={components:{NotepadLines:n.n(i)()},props:{data:{type:Array,required:!0}},computed:{hasBeenAnswered(){return this.data.length>0}}};var r=(0,n("wWJ2").Z)(s,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"tui-linkedReviewParticipantPrint"},[e.hasBeenAnswered?n("div",[e._v(e._s(e.data[0]))]):n("NotepadLines",{attrs:{lines:6}})],1)}),[],!1,null,null,null);r.options.__hasBlocks={script:!0,template:!0};const a=r.exports},r3CX:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>y});const i=tui.require("tui/components/icons/Add");var s=n.n(i);const r=tui.require("tui/components/buttons/Button");var a=n.n(r);const o=tui.require("tui/components/buttons/ButtonIcon");var l=n.n(o),d=n("ifSE"),c=n.n(d);const m=tui.require("tui/components/buttons/CloseIcon");var p=n.n(m),u=n("p202");const _=tui.require("tui/notifications"),h=tui.require("tui/validation"),v={kind:"Document",definitions:[{kind:"OperationDefinition",operation:"mutation",name:{kind:"Name",value:"performelement_linked_review_update_linked_review_content"},variableDefinitions:[{kind:"VariableDefinition",variable:{kind:"Variable",name:{kind:"Name",value:"input"}},type:{kind:"NonNullType",type:{kind:"NamedType",name:{kind:"Name",value:"performelement_linked_review_update_linked_content_input"}}},directives:[]}],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",alias:{kind:"Name",value:"data"},name:{kind:"Name",value:"performelement_linked_review_update_linked_review_content"},arguments:[{kind:"Argument",name:{kind:"Name",value:"input"},value:{kind:"Variable",name:{kind:"Name",value:"input"}}}],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",name:{kind:"Name",value:"validation_info"},arguments:[],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",name:{kind:"Name",value:"can_update"},arguments:[],directives:[]},{kind:"Field",name:{kind:"Name",value:"description"},arguments:[],directives:[]}]}}]}}]}}]},f={components:{AddIcon:s(),Button:a(),ButtonIcon:l(),Card:c(),CloseButton:p(),FormField:u.FormField},props:{addBtnText:{type:String,required:!0},adder:Object,canShowAdder:{type:Boolean,required:!0},cantAddText:{type:String,required:!0},isDraft:Boolean,participantInstanceId:{type:[String,Number],required:!0},removeText:String,required:Boolean,sectionElementId:String,userId:Number,additionalContent:Array,getId:{type:Function,default:e=>"id"in e?e.id:null}},data:()=>({selectedContent:[],selectedIds:[],showAdder:!1}),computed:{id(){return this.$id()},validations(){return this.isDraft?[]:this.selectedIds.length||this.required?[h.v.required()]:[]}},watch:{selectedIds(e){this.$emit("unsaved-plugin-change",{key:this.id,hasChanges:!!e.length})}},methods:{adderClose(){this.showAdder=!1},adderOpen(){this.showAdder=!0},adderUpdate(e){this.selectedContent=e.data,this.selectedIds=e.ids,this.adderClose()},async confirmSelectedIds(){try{await this.saveContent(),this.selectedIds=[]}catch(e){this.showMutationErrorNotification()}},deleteContent(e){this.selectedContent=this.selectedContent.filter((t=>this.getId(t)!==e)),this.selectedIds=this.selectedIds.filter((t=>t!==e))},async saveContent(){await this.$apollo.mutate({mutation:v,variables:{input:{content:JSON.stringify(this.prepareContent()),participant_instance_id:this.participantInstanceId,section_element_id:this.sectionElementId}},refetchAll:!1}).then((({data:e})=>{this.$emit("unsaved-plugin-change",{key:this.id,hasChanges:!1}),e.data.validation_info.can_update?this.$emit("update"):this.$emit("update",e.data.validation_info.description)}))},prepareContent(){return this.selectedContent.map((e=>{let t={id:e.id};return this.additionalContent&&this.additionalContent.forEach((n=>{n in e&&(t[n]=e[n])})),t}))},showMutationErrorNotification(){(0,_.notify)({message:this.$str("error","core"),type:"error"})}}},k=function(e){e.options.__langStrings={core:["error"],mod_perform:["confirm_selection"],performelement_linked_review:["can_not_select_content_message"]}};var g=(0,n("wWJ2").Z)(f,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"tui-linkedReviewSelectedContent"},[n("div",{staticClass:"tui-linkedReviewSelectedContent__items"},e._l(e.selectedContent,(function(t){return n("div",{key:e.getId(t),staticClass:"tui-linkedReviewSelectedContent__item"},[n("Card",{staticClass:"tui-linkedReviewSelectedContent__item-card",attrs:{"no-border":!0}},[n("div",{staticClass:"tui-linkedReviewSelectedContent__item-cardContent"},[e._t("content-preview",null,{content:t})],2),e._v(" "),n("div",{staticClass:"tui-linkedReviewSelectedContent__item-cardActions"},[n("CloseButton",{attrs:{"aria-label":e.removeText,size:300},on:{click:function(n){e.deleteContent(e.getId(t))}}})],1)])],1)})),0),e._v(" "),e.canShowAdder?n("div",[n("ButtonIcon",{attrs:{"aria-label":e.addBtnText,text:e.addBtnText},on:{click:e.adderOpen}},[n("AddIcon")],1),e._v(" "),n(e.adder,{tag:"component",attrs:{open:e.showAdder,"existing-items":e.selectedIds,"user-id":e.userId},on:{added:e.adderUpdate,cancel:e.adderClose}})],1):n("div",[e._v("\n    "+e._s(e.cantAddText)+"\n  ")]),e._v(" "),e.selectedContent.length>0?n("div",{staticClass:"tui-linkedReviewSelectedContent__confirm"},[n("Button",{attrs:{text:e.$str("confirm_selection","mod_perform"),styleclass:{primary:!0}},on:{click:e.confirmSelectedIds}})],1):e._e(),e._v(" "),n("FormField",{attrs:{name:e.$id("contentAdder"),validations:e.validations}})],1)}),[],!1,null,null,null);k(g),g.options.__hasBlocks={script:!0,template:!0};const y=g.exports},bBKE:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>d});var i=n("p202"),s=n("Y2he"),r=n.n(s);const a={components:{FormRadioGroup:i.FormRadioGroup,FormRow:i.FormRow,Radio:r()},props:{fieldName:{type:String,required:!0},helpMsg:String,label:{type:String,required:!0},relationships:Array}},o=function(e){e.options.__langStrings={mod_perform:["no_participants"]}};var l=(0,n("wWJ2").Z)(a,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("FormRow",{attrs:{helpmsg:e.helpMsg,label:e.label,required:!0}},[e.relationships.length<1?n("div",[e._v("\n    "+e._s(e.$str("no_participants","mod_perform"))+"\n  ")]):e._e(),e._v(" "),n("FormRadioGroup",{attrs:{name:e.fieldName,validations:function(e){return[e.required()]}}},e._l(e.relationships,(function(t){return n("Radio",{key:t.id,attrs:{value:t.id}},[e._v("\n      "+e._s(t.name)+"\n    ")])})),1)],1)}),[],!1,null,null,null);o(l),l.options.__hasBlocks={script:!0,template:!0};const d=l.exports},wWJ2:(e,t,n)=>{"use strict";function i(e,t,n,i,s,r,a,o){var l,d="function"==typeof e?e.options:e;if(t&&(d.render=t,d.staticRenderFns=n,d._compiled=!0),i&&(d.functional=!0),r&&(d._scopeId="data-v-"+r),a?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),s&&s.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(a)},d._ssrRegister=l):s&&(l=o?function(){s.call(this,(d.functional?this.parent:this).$root.$options.shadowRoot)}:s),l)if(d.functional){d._injectStyles=l;var c=d.render;d.render=function(e,t){return l.call(t),c(e,t)}}else{var m=d.beforeCreate;d.beforeCreate=m?[].concat(m,l):[l]}return{exports:e,options:d}}n.d(t,{Z:()=>i})},ifSE:e=>{"use strict";e.exports=tui.require("tui/components/card/Card")},Y2he:e=>{"use strict";e.exports=tui.require("tui/components/form/Radio")},U9m6:e=>{"use strict";e.exports=tui.require("tui/components/reform/FormScope")},p202:e=>{"use strict";e.exports=tui.require("tui/components/uniform")}},t={};function n(i){var s=t[i];if(void 0!==s)return s.exports;var r=t[i]={exports:{}};return e[i](r,r.exports,n),r.exports}n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var i in t)n.o(t,i)&&!n.o(e,i)&&Object.defineProperty(e,i,{enumerable:!0,get:t[i]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),n.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},function(){"use strict";"undefined"!=typeof tui&&tui._bundle.isLoaded("performelement_linked_review")?console.warn('[tui bundle] The bundle "performelement_linked_review" is already loaded, skipping initialisation.'):(tui._bundle.register("performelement_linked_review"),tui._bundle.addModulesFromContext("performelement_linked_review/components",n("BU7s")))}()})();