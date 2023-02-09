(()=>{var e={dASd:(e,t,n)=>{var a={"./nodes/Placeholder":"yVOz","./nodes/Placeholder.vue":"yVOz","./suggestion/Placeholder":"Gd0G","./suggestion/Placeholder.vue":"Gd0G"};function i(e){var t=o(e);return n(t)}function o(e){if(!n.o(a,e)){var t=new Error("Cannot find module '"+e+"'");throw t.code="MODULE_NOT_FOUND",t}return a[e]}i.keys=function(){return Object.keys(a)},i.resolve=o,e.exports=i,i.id="dASd"},rbU4:(e,t,n)=>{var a={"./extension":"61Q6","./extension.js":"61Q6","./plugin":"75Tf","./plugin.js":"75Tf"};function i(e){var t=o(e);return n(t)}function o(e){if(!n.o(a,e)){var t=new Error("Cannot find module '"+e+"'");throw t.code="MODULE_NOT_FOUND",t}return a[e]}i.keys=function(){return Object.keys(a)},i.resolve=o,e.exports=i,i.id="rbU4"},yVOz:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>l});const a=tui.require("editor_weka/components/nodes/BaseNode");var i=n.n(a);const o=tui.require("totara_notification/components/json_editor/nodes/Placeholder"),r={components:{Placeholder:n.n(o)()},extends:i(),computed:{placeholderKey(){const e=this.attrs;return e.key?e.key:""},displayName(){const e=this.attrs;return e.label?e.label:""}}};var s=(0,n("wWJ2").Z)(r,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("Placeholder",{attrs:{"placeholder-key":e.placeholderKey,"display-name":e.displayName}})}),[],!1,null,null,null);s.options.__hasBlocks={script:!0,template:!0};const l=s.exports},Gd0G:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>p});const a=tui.require("tui/components/dropdown/Dropdown");var i=n.n(a);const o=tui.require("tui/components/dropdown/DropdownItem");var r=n.n(o);const s=tui.require("tui/components/loading/Loader");var l=n.n(s);const d={components:{Dropdown:i(),DropdownItem:r(),Loader:l()},props:{contextId:{type:[Number,String],required:!0},resolverClassName:{type:String,required:!0},location:{required:!0,type:Object},pattern:{required:!0,type:String}},apollo:{placeholders:{query:{kind:"Document",definitions:[{kind:"OperationDefinition",operation:"query",name:{kind:"Name",value:"weka_notification_placeholder_placeholders"},variableDefinitions:[{kind:"VariableDefinition",variable:{kind:"Variable",name:{kind:"Name",value:"context_id"}},type:{kind:"NonNullType",type:{kind:"NamedType",name:{kind:"Name",value:"param_integer"}}},directives:[]},{kind:"VariableDefinition",variable:{kind:"Variable",name:{kind:"Name",value:"pattern"}},type:{kind:"NonNullType",type:{kind:"NamedType",name:{kind:"Name",value:"param_text"}}},directives:[]},{kind:"VariableDefinition",variable:{kind:"Variable",name:{kind:"Name",value:"resolver_class_name"}},type:{kind:"NonNullType",type:{kind:"NamedType",name:{kind:"Name",value:"param_text"}}},directives:[]}],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",alias:{kind:"Name",value:"placeholders"},name:{kind:"Name",value:"weka_notification_placeholder_placeholders"},arguments:[{kind:"Argument",name:{kind:"Name",value:"context_id"},value:{kind:"Variable",name:{kind:"Name",value:"context_id"}}},{kind:"Argument",name:{kind:"Name",value:"pattern"},value:{kind:"Variable",name:{kind:"Name",value:"pattern"}}},{kind:"Argument",name:{kind:"Name",value:"resolver_class_name"},value:{kind:"Variable",name:{kind:"Name",value:"resolver_class_name"}}}],directives:[],selectionSet:{kind:"SelectionSet",selections:[{kind:"Field",name:{kind:"Name",value:"__typename"},arguments:[],directives:[]},{kind:"Field",name:{kind:"Name",value:"label"},arguments:[],directives:[]},{kind:"Field",name:{kind:"Name",value:"key"},arguments:[],directives:[]}]}}]}}]},fetchPolicy:"network-only",variables(){return{pattern:this.pattern,context_id:this.contextId,resolver_class_name:this.resolverClassName}}}},data:()=>({placeholders:[]}),computed:{showSuggestions(){return this.$apollo.loading||this.placeholders.length>0},positionStyle(){return{left:`${this.location.x}px`,top:`${this.location.y}px`}}},watch:{showSuggestions(e){e||this.$emit("dismiss")}},methods:{pickPlaceholder({key:e,label:t}){this.$emit("item-selected",{id:e,text:t})}}},c=function(e){e.options.__langStrings={editor_weka:["matching_placeholders"]}};var u=(0,n("wWJ2").Z)(d,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"tui-wekaPlaceholderSuggestion",style:e.positionStyle},[n("Dropdown",{attrs:{separator:!0,open:e.showSuggestions,"inline-menu":!0},on:{dismiss:function(t){return e.$emit("dismiss")}}},[n("span",{staticClass:"sr-only"},[e._v("\n      "+e._s(e.$str("matching_placeholders","editor_weka"))+":\n    ")]),e._v(" "),e.$apollo.loading?[n("DropdownItem",{attrs:{disabled:!0}},[n("Loader",{attrs:{loading:!0}})],1)]:e._e(),e._v(" "),e._l(e.placeholders,(function(t,a){return n("DropdownItem",{key:a,attrs:{"no-padding":!0},on:{click:function(n){return e.pickPlaceholder(t)}}},[n("span",{staticClass:"tui-wekaPlaceholderSuggestion__label"},[e._v("\n        "+e._s(t.label)+"\n      ")])])}))],2)],1)}),[],!1,null,null,null);c(u),u.options.__hasBlocks={script:!0,template:!0};const p=u.exports},"61Q6":(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>d});const a=tui.require("weka_notification_placeholder/components/nodes/Placeholder");var i=n.n(a);const o=tui.require("editor_weka/extensions/Base");var r=n.n(o),s=n("75Tf");class l extends(r()){nodes(){return{totara_notification_placeholder:{schema:{group:"inline",inline:!0,attrs:{key:{default:void 0},label:{default:void 0}},parseDOM:[{tag:"span.tui-placeholder__text",getAttrs(e){try{return{key:e.getAttribute("data-key"),label:e.getAttribute("data-label")}}catch(e){return{}}}}],toDOM:e=>["span",{class:"tui-placeholder__text","data-key":e.attrs.key,"data-label":e.attrs.label},"["+e.attrs.label+"]"]},component:i()}}}plugins(){return[(0,s.default)(this.editor,this.options.resolver_class_name)]}}const d=e=>new l(e)},"75Tf":(e,t,n)=>{"use strict";n.r(t),n.d(t,{REGEX:()=>d,default:()=>c});const a=tui.require("tui/util"),i=tui.require("ext_prosemirror/state"),o=tui.require("editor_weka/helpers/suggestion");var r=n.n(o);const s=tui.require("weka_notification_placeholder/components/suggestion/Placeholder");var l=n.n(s);const d=new RegExp("\\[([a-z_:]+]?)?$","ig");function c(e,t){const n=new i.PluginKey("placeholders");let o=new(r())(e);return new i.Plugin({key:n,view(){return{update:(0,a.debounce)((n=>{const{text:a,active:i,range:r}=this.key.getState(n.state);if(o.destroyInstance(),!a||!i)return;if(!n.editable)return;const s=a.slice(1);o.showList({view:n,component:{name:"totara_notification_placeholder",component:l(),attrs:(e,t)=>({key:e,label:t}),props:{resolverClassName:t,contextId:e.identifier.contextId,pattern:s}},state:{text:s,active:i,range:r}})}),250)}},state:{init:()=>({active:!1,range:{},text:null}),apply:(e,t)=>(d.lastIndex=0,o.apply(e,t,d))},props:{handleKeyDown(e,t){if("Escape"===t.key||"Esc"===t.key){const{active:n}=this.getState(e.state);return!!n&&(o.destroyInstance(),e.focus(),t.stopPropagation(),!0)}}}})}},wWJ2:(e,t,n)=>{"use strict";function a(e,t,n,a,i,o,r,s){var l,d="function"==typeof e?e.options:e;if(t&&(d.render=t,d.staticRenderFns=n,d._compiled=!0),a&&(d.functional=!0),o&&(d._scopeId="data-v-"+o),r?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),i&&i.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(r)},d._ssrRegister=l):i&&(l=s?function(){i.call(this,(d.functional?this.parent:this).$root.$options.shadowRoot)}:i),l)if(d.functional){d._injectStyles=l;var c=d.render;d.render=function(e,t){return l.call(t),c(e,t)}}else{var u=d.beforeCreate;d.beforeCreate=u?[].concat(u,l):[l]}return{exports:e,options:d}}n.d(t,{Z:()=>a})}},t={};function n(a){var i=t[a];if(void 0!==i)return i.exports;var o=t[a]={exports:{}};return e[a](o,o.exports,n),o.exports}n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var a in t)n.o(t,a)&&!n.o(e,a)&&Object.defineProperty(e,a,{enumerable:!0,get:t[a]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),n.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},function(){"use strict";"undefined"!=typeof tui&&tui._bundle.isLoaded("weka_notification_placeholder")?console.warn('[tui bundle] The bundle "weka_notification_placeholder" is already loaded, skipping initialisation.'):(tui._bundle.register("weka_notification_placeholder"),tui._bundle.addModulesFromContext("weka_notification_placeholder",n("rbU4")),tui._bundle.addModulesFromContext("weka_notification_placeholder/components",n("dASd")))}()})();