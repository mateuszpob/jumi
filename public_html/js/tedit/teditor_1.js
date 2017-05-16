var Treeeditor = function(container, iframe){
    this.container = container;
    this.iframe = iframe;
    this.activeEdition = false;
    this.range = null;
    this.toolbar = null;
    this.mainToolbar = null;
    this.dragSrcEl = null;
    this.activeElement = null;
    this.insertAfter = null;
    this.hoverElement = null;
    this.fromContextMenu = false;
    this.init();
};

Treeeditor.elements = [];
Treeeditor.snipets = [];

/**
 * Initialize container, adds necessary event listeners
 */
Treeeditor.prototype.init = function(){
    this.container.classList.add('treeeditor-editable');
    this.addMainToolbar();


    this.container.addEventListener('contextmenu', this.handlerContextMenu.bind(this), true);
    this.contextMenu.addEventListener('mouseout', this.handlerContextMenuOut.bind(this), false);
    this.container.addEventListener('click', this.handlerClick.bind(this), true);
    this.container.addEventListener('mouseover', this.handlerOver.bind(this), true);
    if(this.iframe){
    	this.iframe.addEventListener('mouseout', this.handlerOut.bind(this), true);
    	this.iframe.addEventListener('mouseenter', this.handlerEnter.bind(this), true);
    } else {
    	this.container.addEventListener('mouseout', this.handlerOut.bind(this), true);
    	this.container.addEventListener('mouseenter', this.handlerEnter.bind(this), true);
    }
    this.container.addEventListener('scroll', this.handlerOver.bind(this), true);
    [].forEach.call(document.querySelectorAll('.active-only'), function(element){element.setAttribute('disabled',true);});
    [].forEach.call(this.container.querySelectorAll('*'), function(element){this.makeDraggable(element);}.bind(this));


    this.container.addEventListener('click', this.stopFollowLinks.bind(this), true);
    this.refreshTree();
};

/**
 * Prevents folow hyperlinks when editing
 */
Treeeditor.prototype.stopFollowLinks = function(event)
{
	event.preventDefault();
}

/**
 * Adds toolbar button
 * @param toolbar
 * @param params
 */
Treeeditor.prototype.addToolbarButton = function(toolbar, params)
{
    var fragment = document.createDocumentFragment();
    var button = fragment.appendChild(document.createElement('BUTTON'));
    button.setAttribute('type', 'button');
    params.classNames.forEach(function(name){
	    button.classList.add(name);
	});
    button.setAttribute('title', params.title);
    button.addEventListener('click', params.handler.bind(this), true);
    if(params.content)
    {
        button.appendChild(document.createTextNode(params.content));
    }
    toolbar.appendChild(fragment);
};

/**
 * Creates toolbars, main and context.
 */
Treeeditor.prototype.addMainToolbar = function()
{
    var fragment = document.createDocumentFragment();
    this.mainToolbar = fragment.appendChild(document.createElement('DIV'));
    this.mainToolbar.style.position = 'absolute';
    this.mainToolbar.classList.add('tree-edit-toolbar','container-toolbar');
    this.addToolbarButton(this.mainToolbar, {classNames:['start-edit','fa','fa-navicon'], title:'Show tree', handler: this.switchTree.bind(this)});
    this.addToolbarButton(this.mainToolbar, {classNames:['start-edit','fa','fa-pencil', 'edit-text', 'active-only'], title:'Edit as text', handler: this.switchEditing.bind(this)});
    this.addToolbarButton(this.mainToolbar, {classNames:['start-edit','fa','fa-gear', 'active-only'], title:'Edit properties', handler: this.dialogEdit.bind(this)});
    this.addToolbarButton(this.mainToolbar, {classNames:['start-edit','fa','fa-file-code-o'], title:'Edit code', handler: this.dialogCode.bind(this)});
    this.mainToolbar.insertAdjacentHTML('beforeend', '<div class="spacer start-edit"></div>');
    this.addToolbarButton(this.mainToolbar, {classNames:['start-edit','fa','fa-plus','active-only'], title:'Create child element', handler: this.insertElement.bind(this, null)});
    this.addToolbarButton(this.mainToolbar, {classNames:['start-edit','fa','fa-arrow-up','active-only'], title:'Insert element before', handler: this.insertElementBefore.bind(this, null)});
    this.addToolbarButton(this.mainToolbar, {classNames:['start-edit','fa','fa-arrow-down','active-only'], title:'Insert element after', handler: this.insertElementAfter.bind(this, null)});
    this.mainToolbar.insertAdjacentHTML('beforeend', '<div class="spacer start-edit"></div>');
    this.addToolbarButton(this.mainToolbar, {classNames:['start-edit','cancel','fa','fa-minus','active-only'], title:'Remove active element', handler: this.removeElement.bind(this)});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-undo',], title: 'Undo',handler: this.execCommand.bind(this, 'undo')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-repeat'], title:'Redo', handler: this.execCommand.bind(this, 'redo')});
    this.mainToolbar.insertAdjacentHTML('beforeend', '<div class="spacer text-edit"></div>');
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-bold',], title: 'Bold', handler: this.execCommand.bind(this, 'Bold')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-italic'], title: 'Italic', handler: this.execCommand.bind(this, 'Italic')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-strikethrough'],title:'Strike through', handler: this.execCommand.bind(this, 'strikeThrough')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-subscript'],title:'Subscript',handler: this.execCommand.bind(this, 'subscript')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-superscript'],title:'Superscript', handler: this.execCommand.bind(this, 'superscript')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-underline'], title:'Underline', handler: this.execCommand.bind(this, 'underline')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-eraser'], title:'Remove format', handler: this.execCommandConfirm.bind(this, 'removeFormat', 'Remove format?')});
    this.mainToolbar.insertAdjacentHTML('beforeend', '<div class="spacer text-edit"></div>');
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-align-left'], title:'Justify left', handler: this.justify.bind(this, 'left')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-align-center'], title:'Justify center', handler: this.justify.bind(this, 'center')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-align-right'], title:'Justify right', handler: this.justify.bind(this, 'right')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-align-justify'], title:'Justify Full', handler: this.justify.bind(this, 'justify')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-indent'], title:'Indent', handler: this.execCommand.bind(this, 'indent')});
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-outdent'], title: 'Outdent', handler: this.execCommand.bind(this, 'outdent')});
    this.mainToolbar.insertAdjacentHTML('beforeend', '<div class="spacer text-edit"></div>');
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','fa','fa-link'], title: 'Create link', handler: this.execCommandPrompt.bind(this, 'createLink', 'URL')});
    this.mainToolbar.insertAdjacentHTML('beforeend', '<div class="spacer text-edit"></div>');
    Treeeditor.snipets.forEach(function(element){
        Treeeditor.prototype.addToolbarButton.call(this, this.mainToolbar, element);
    }.bind(this));
    this.mainToolbar.insertAdjacentHTML('beforeend', '<div class="spacer text-edit"></div>');
    this.addToolbarButton(this.mainToolbar, {classNames:['text-edit','save','fa','fa-check'], title: 'Save', handler: this.switchEditing.bind(this)});
    document.body.appendChild(fragment);

    var fragment = document.createDocumentFragment();
    this.contextMenu = fragment.appendChild(document.createElement('DIV'));
    this.contextMenu.style.position = 'absolute';
    this.contextMenu.style.display = 'none';
    this.contextMenu.classList.add('tree-edit-toolbar','container-toolbar');
    this.addToolbarButton(this.contextMenu, {classNames:['start-edit','fa','fa-pencil', 'edit-text'], title:'Edit as text', handler: this.switchEditing.bind(this)});
    this.addToolbarButton(this.contextMenu, {classNames:['start-edit','fa','fa-gear'], title:'Edit properties', handler: this.dialogEdit.bind(this)});
    this.addToolbarButton(this.contextMenu, {classNames:['start-edit','fa','fa-file-code-o'], title:'Edit code', handler: this.dialogCode.bind(this)});
    this.contextMenu.insertAdjacentHTML('beforeend', '<div class="spacer"></div>');
//    this.addToolbarButton(this.contextMenu, {classNames:['start-edit','fa','fa-plus'], title:'Create child element', handler: this.insertElement.bind(this, null)});
    this.addToolbarButton(this.contextMenu, {classNames:['start-edit','fa','fa-arrow-up'], title:'Insert element before', handler: this.insertElementBefore.bind(this, null)});
    this.addToolbarButton(this.contextMenu, {classNames:['start-edit','fa','fa-arrow-down'], title:'Insert element after', handler: this.insertElementAfter.bind(this, null)});
    this.contextMenu.insertAdjacentHTML('beforeend', '<div class="spacer"></div>');
    this.addToolbarButton(this.contextMenu, {classNames:['start-edit','cancel','fa','fa-minus'], title:'Remove current element', handler: this.removeElement.bind(this)});
    document.body.appendChild(fragment);
    this.resize();
};

/**
 * Makes element draggable
 * @param container
 */
Treeeditor.prototype.makeDraggable = function(container){
    container.draggable = true;
    container.addEventListener('dragstart', this.handlerDragStart.bind(this), false);
    container.addEventListener('dragenter', this.handlerDragEnter.bind(this), true);
    container.addEventListener('dragover', this.handlerDragOver.bind(this), false);
    container.addEventListener('dragleave', this.handlerDragLeave.bind(this), false);
    container.addEventListener('dragend', this.handlerDragEnd.bind(this), false);
    container.addEventListener('drop', this.handlerDrop.bind(this), false);
};

/**
 * Switches element activity. If parameters is ommited removes removes active element
 * @param element
 */
Treeeditor.prototype.switchActive = function(element){
    if(!this.activeEdition){
	    if(element && element != this.activeElement){
	        if(this.activeEdition)
	            this.switchEditing();
	        this.insertBefore = null;
	        this.insertAfter = null;
        	this.hoverElement = null;
        	[].forEach.call(this.container.querySelectorAll('.treeeditor-hover'), function(element){element.classList.remove('treeeditor-hover');});
            this.container.classList.remove('treeeditor-active');
            [].forEach.call(this.container.querySelectorAll('.treeeditor-active'), function(element){element.classList.remove('treeeditor-active');});
            this.activeElement = element;
            this.activeElement.classList.add('treeeditor-active');
            if(!this.activeElement.isInViewport())
                this.activeElement.scrollIntoView(true);
            if(this.tree){
           		[].forEach.call(this.tree.querySelectorAll('.treeeditor-hover'), function(element){element.classList.remove('treeeditor-hover');});
                [].forEach.call(this.tree.querySelectorAll('.treeeditor-active'), function(element){element.classList.remove('treeeditor-active');});
                var id = this.activeElement.getAttribute('data-treeeditor-id');
    	        var treeElement = this.tree.querySelector('[data-treeeditor-id="'+id+'"]');
                treeElement.parentNode.classList.add('treeeditor-active');
                var treeElementRect = treeElement.getBoundingClientRect();
                var treeRect = this.tree.getBoundingClientRect();
                if(!treeElement.isInViewport() || treeElementRect.y - this.tree.scrollTop < 0 || treeElementRect.y-this.tree.scrollTop+treeElementRect.height > treeRect.height)
                    treeElement.scrollIntoView(true);
            };
            [].forEach.call(document.querySelectorAll('.active-only'), function(element){element.removeAttribute('disabled');});
	    } else {
	    	this.activeElement = null;
            if(this.tree)
                [].forEach.call(this.tree.querySelectorAll('.treeeditor-active'), function(element){element.classList.remove('treeeditor-active');});
            element.classList.remove('treeeditor-active');
            [].forEach.call(document.querySelectorAll('.active-only'), function(element){element.setAttribute('disabled',true);});
	    }
    }
};


Treeeditor.prototype.justify = function(direction){
    switch (direction) {
        case 'right':
            this.activeElement.style.textAlign = 'right';
            break;

        case 'justify':
            this.activeElement.style.textAlign = 'justify';
            break;

        case 'center':
            this.activeElement.style.textAlign = 'center';
            break;

        default:
            this.activeElement.style.textAlign = '';
    }
};

Treeeditor.prototype.switchEditing = function(element){
    if(this.activeElement){
        if(!this.activeEdition) {
        	this.contextMenu.style.display = 'none';
        	[].forEach.call(this.container.querySelectorAll('.treeeditor-hover'), function(element){element.classList.remove('treeeditor-hover');});
            this.activeElement.classList.remove('treeeditor-active');
            this.activeEdition = true;
            this.mainToolbar.classList.add('active-text');
            this.activeElement.setAttribute('contenteditable', true);
            this.activeElement.setAttribute('data-draggable', this.activeElement.getAttribute('draggable'));
            this.activeElement.setAttribute('draggable', false);
            var element = this.activeElement.parentNode;
            while (element.parentNode) {
                element.setAttribute('data-draggable', element.getAttribute('draggable'));
                element.setAttribute('draggable', false);
                element = element.parentNode;
            }
            this.execCommand('styleWithCSS', this.styleWithCSS);
            this.execCommand('insertBrOnReturn', this.insertBrOnReturn);
            this.execCommand('defaultParagraphSeparator', '<p>');
            this.activeElement.focus();
        } else {
        	this.activeEdition = false;
            this.activeElement.setAttribute('contenteditable', false);
            this.mainToolbar.classList.remove('active-text');
            this.activeElement.setAttribute('draggable', this.activeElement.getAttribute('data-draggable'));
            this.activeElement.removeAttribute('data-draggable');
            var element = this.activeElement.parentNode;
            while (element.parentNode) {
                element.setAttribute('contenteditable', false);
                element.setAttribute('draggable', element.getAttribute('data-draggable'));
                element.removeAttribute('data-draggable');
                element = element.parentNode;
            }
            if(this.fromContextMenu){
            	this.fromContexMenu = false;
            	this.hooverElement = this.activeElement;
            	this.hooverElement.classList.add('treeeditor-hover');
            	this.switchActive();
            } else {
            	this.activeElement.classList.add('treeeditor-active');
            }
            this.refreshTree();
        }
    }
};

Treeeditor.prototype.removeElement = function(){
	this.contextMenu.style.display = 'none';
	this.activeElement.classList.remove('treeeditor-hover');
	this.activeElement.classList.remove('treeeditor-active');
	if(this.activeElement != this.container){
		if(confirm('Are you sure?')) {
			this.activeElement.remove();
	        this.activeElement =  null;
	        this.refreshTree();
	        [].forEach.call(document.querySelectorAll('.active-only'), function(element){element.setAttribute('disabled',true);});
	    }
	}
};

Treeeditor.prototype.execCommandPrompt = function(command, label){
    var parameter = prompt(label);
    if(this.iframe)
        this.iframe.contentDocument.execCommand(command, false, parameter);
    else
        document.execCommand(command, false, parameter);
};

Treeeditor.prototype.execCommandConfirm = function(command, question, parameter){
    if(confirm(question)) {
        if(this.iframe)
            this.iframe.contentDocument.execCommand(command, false, parameter);
        else
            document.execCommand(command, false, parameter);
    }
};

Treeeditor.prototype.execCommand = function(command, parameter){
    if(this.iframe)
        this.iframe.contentDocument.execCommand(command, false, parameter);
    else
        document.execCommand(command, false, parameter);
};

Treeeditor.prototype.insertElement = function(tagName, attributes, styles){
    if(tagName){
        var element = document.createElement(tagName);
        if(attributes)
        	for(var name in attributes)
        		element.setAttribute(name, attributes[name]);
        if(styles)
        	for(var name in styles)
        		element.style[name] = styles[name];

        if(this.insertBefore){
        	this.insertBefore.parentNode.insertBefore(element, this.insertBefore);
        	this.insertBefore = null;
        } else if(this.insertAfter){
        	this.insertAfter.parentNode.insertBefore(element, this.insertAfter.nextSibling);
        	this.insertAfter = null;
        } else if(this.activeElement){
        	this.activeElement.appendChild(element);
        } else {
        	this.container.appendChild(element);
        }
        this.makeDraggable(element);
        this.refreshTree();
        this.switchActive(element);
        if(Treeeditor.tags[tagName.toLowerCase()].next){
            switch(Treeeditor.tags[tagName.toLowerCase()].next){
                case 'edit':
                    this.switchEditing(element);
                    break;
                case 'build':
                    this.insertElement();
                    break;
                default:
           }
        } else {
            this.dialogEdit();
        }
        return element;
    } else {
    	if(this.insertBefore){
    		var parentTag = this.insertBefore.parentNode.tagName.toLowerCase();
    	} else if(this.insertAfter){
    		var parentTag = this.insertAfter.parentNode.tagName.toLowerCase();
    	} else if (this.activeElement){
    		var parentTag = this.activeElement.tagName.toLowerCase();
    	} else {
    		this.activeElement = this.container;
    		var parentTag = this.activeElement.tagName.toLowerCase();
    	}
    	this.contextMenu.style.display = 'none';
    	[].forEach.call(this.container.querySelectorAll('.treeeditor-hover'), function(element){element.classList.remove('treeeditor-hover');});
    	[].forEach.call(this.container.querySelectorAll('.treeeditor-over'), function(element){element.classList.remove('treeeditor-over');});
        this.container.classList.remove('treeeditor-active');
        var dialog = this.addDialog('Insert node', this.insertElement.bind(this));
        var page = this.addDialogPage(dialog, null, null, false);
        var groups = {};
        for(var name in Treeeditor.groups){
        	groups[name] = page.appendChild(document.createElement('FIELDSET'));
        	var legend = groups[name].appendChild(document.createElement('LEGEND'));
        	legend.appendChild(document.createTextNode(Treeeditor.groups[name].name));
        }
        if(!Treeeditor.tags[parentTag.toLowerCase()] || !Treeeditor.tags[parentTag.toLowerCase()].children){
	        Treeeditor.elements.forEach(function(element){
	            var fragment = document.createDocumentFragment();
	            var button = fragment.appendChild(document.createElement('BUTTON'));
	            button.setAttribute('type', 'button');
	            element.classNames.forEach(function(name){
	                button.classList.add(name);
	            });
	            button.setAttribute('title', element.title);
	            button.appendChild(document.createTextNode(element.name));
	            button.addEventListener('click', element.handler.bind(this), true);
	            groups['custom'].appendChild(fragment);
	        }.bind(this));
        }
    	var names = Treeeditor.tags[parentTag.toLowerCase()] && Treeeditor.tags[parentTag.toLowerCase()].children ? Treeeditor.tags[parentTag.toLowerCase()].children : Object.getOwnPropertyNames(Treeeditor.tags).sort();
        names.forEach(function(name) {
        	if(name != 'common'){
        		if(!groups[Treeeditor.tags[name].category])
            		alert(Treeeditor.tags[name].category + ' ' + name);
                var button = groups[Treeeditor.tags[name].category].appendChild(document.createElement('BUTTON'));
                button.setAttribute('type', 'button');
    	    	if(Treeeditor.tags[name].parent && Treeeditor.tags[name].parent.indexOf(parentTag) < 0) {
    	            button.setAttribute('disabled', 'disabled');
    	            button.appendChild(document.createTextNode(name));
    	    	} else {
    	            button.setAttribute('title', Treeeditor.tags[name].description);
    	            button.appendChild(document.createTextNode(name));
    	            button.addEventListener('click', function(){
    	                this.closeDialog();
    	                this.insertElement(name, true);
    	            }.bind(this));
    	        }
        	}
        }.bind(this));

        [].forEach.call(Object.getOwnPropertyNames(groups), function(name){
        	if(groups[name].children.length == 1)
        		groups[name].remove();
        }.bind(this));

        dialog.parentNode.querySelector('button.save').remove();
        this.addDialog();
    }
};

Treeeditor.prototype.dialogParameters = function(params){
    var form = document.querySelector('#treeeditor-dialog-parameters form');
    if(form){
        var elements = form.elements;
        var newParams = {};
        for(var name in params.params) {
            if(elements.namedItem(name))
                newParams[name] = elements.namedItem(name).value;
        }
        params.command.call(this, newParams);
        this.closeDialog();
    } else {
    	this.contextMenu.style.display = 'none';
        var dialog = this.addDialog(params.title || '',  this.dialogParameters.bind(this, params), 'treeeditor-dialog-parameters');
        var table = dialog.appendChild(document.createElement('table'));
        var colgroup = table.appendChild(document.createElement('colgroup'));
        colgroup.appendChild(document.createElement('col')).style.width = '50%';
        colgroup.appendChild(document.createElement('col'));
        for (var name in params.params) {
            var param = params.params[name];
            switch (param.type) {
            case 'select':
                var row = table.insertRow(-1);
                var column = row.insertCell(0);
                column.appendChild(document.createTextNode(param.label));
                var column = row.insertCell(1);
                var select = column.appendChild(document.createElement('SELECT'));
                select.setAttribute('name', name);
                for(var value in param.values)
                {
                    var option = select.appendChild(document.createElement('OPTION'));
                    option.setAttribute('value', value);
                    option.appendChild(document.createTextNode(param.values[value]));
                }
                break;
            case 'ajax':
                var row = table.insertRow(-1);
                var column = row.insertCell(0);
                column.colSpan = 2;
                column.load(param.url, true);
                break;
            case 'alternatives':
                for(var key in param.values){
                    var row = table.insertRow(-1);
                    var column = row.insertCell(0);
                    column.appendChild(document.createTextNode(param.values[key]));
                    var column = row.insertCell(1);
                    var input = column.appendChild(document.createElement('INPUT'));
                    input.setAttribute('type', 'radio');
                    input.setAttribute('name', name);
                    input.setAttribute('value', key);
                }
                break;
            case 'list':
                for(var key in param.values){
                    var row = table.insertRow(-1);
                    var column = row.insertCell(0);
                    column.appendChild(document.createTextNode(param.values[key]));
                    var column = row.insertCell(1);
                    var input = column.appendChild(document.createElement('INPUT'));
                    input.setAttribute('type', 'checkbox');
                    input.setAttribute('name', name);
                    input.setAttribute('value', key);
                }
                break;
            default:
                var row = table.insertRow(-1);
                var column = row.insertCell(0);
                column.appendChild(document.createTextNode(param.label));
                var column = row.insertCell(1);
                var input = column.appendChild(document.createElement('INPUT'));
                input.setAttribute('type', 'text');
                input.setAttribute('name', name);
                break;
            }
        }
        this.addDialog();
    }
};

Treeeditor.prototype.closeDialog = function(event)
{
    if(!event || event.currentTarget == event.target)
    {
        var disabler = document.querySelector('.treeeditor-disabler');
        if(disabler)
            disabler.parentNode.removeChild(disabler);
        if(this.fromContextMenu){
        	this.fromContexMenu = false;
        	this.hooverElement = this.activeElement;
        	if(this.hooverElement)
        		this.hooverElement.classList.add('treeeditor-hover');
        	this.switchActive(this.activeElement);
        } else {
            if(this.activeElement)
            	this.activeElement.classList.add('treeeditor-active');
        }
    }
};

Treeeditor.prototype.handlerDragStart =  function (event) {
      event.currentTarget.classList.add('treeeditor-drag');
      this.dragSrcEl = event.currentTarget;
      event.dataTransfer.effectAllowed = 'move';
      event.dataTransfer.setData('text/html', this.innerHTML);
  };

Treeeditor.prototype.handlerDragOver = function(event) {
    if (event.preventDefault) {
        event.preventDefault();
    }
    event.dataTransfer.dropEffect = 'move';
    return false;
};

Treeeditor.prototype.handlerDragEnter = function(event) {
    event.currentTarget.classList.add('treeeditor-over');
};

Treeeditor.prototype.handlerDragLeave = function(event) {
	[].forEach.call(this.container.querySelectorAll('.treeeditor-over'), function(element){element.classList.remove('treeeditor-over');});
};

Treeeditor.prototype.handlerDrop =  function(event) {
    event.stopPropagation();
    event.preventDefault();
    event.currentTarget.classList.remove('treeeditor-over');
    if (this.dragSrcEl != this) {
        var siblings = this.dragSrcEl.parentNode.childNodes;
        var before = true;
        for (var i = 0; i < siblings.length; ++i) {
            var node = siblings[i];
            if(node == this.dragSrcEl)
                before = false;
            if(node == event.currentTarget) {
                if(before) {
                    event.currentTarget.parentNode.insertBefore(this.dragSrcEl, event.target);
                } else {
                    if(node.nextSibling)
                        event.currentTarget.parentNode.insertBefore(this.dragSrcEl, node.nextSibling);
                    else
                        event.currentTarget.parentNode.appendChild(this.dragSrcEl);
                }
                return false;
            }
        }
        if(event.currentTarget.tagName.toLowerCase() === 'div')
            event.currentTarget.appendChild(this.dragSrcEl);
        if(event.currentTarget.parentNode.tagName.toLowerCase() === 'div')
            event.currentTarget.parentNode.appendChild(this.dragSrcEl);
    }
    return false;
};

Treeeditor.prototype.handlerDragEnd = function (event) {
	[].forEach.call(this.container.querySelectorAll('.treeeditor-over'), function(element){element.classList.remove('treeeditor-over');});
	[].forEach.call(this.container.querySelectorAll('.treeeditor-drag'), function(element){element.classList.remove('treeeditor-drag');});
};


Treeeditor.prototype.insertElementBefore = function(){
	if(this.activeElement)
		this.insertBefore = this.activeElement;
	else
		this.insertBefore = this.hoverElement;
	this.insertAfter = null;
	this.insertElement();
};


Treeeditor.prototype.insertElementAfter = function(){
	if(this.activeElement)
		this.insertAfter = this.activeElement;
	else
		this.insertAfter = this.hoverElement;
	this.insertBefore = null;
	this.insertElement();
};


Treeeditor.prototype.html = function(content){
    if(content){
        if(this.iframe){
            this.iframe.srcdoc = content;
        } else {
            this.container.innerHTML = content;
        }
        this.refreshTree();
    } else {
        if(this.iframe){
            var content = this.iframe.contentDocument.cloneNode(true);
            content.documentElement.removeAttribute('draggable');
            content.documentElement.removeAttribute('hasbrowserhandlers');
            content.documentElement.removeAttribute('contenteditable');
            content.body = this.removeMeta(content.body);
            var style = content.head.querySelector('#treeeditor-style');
            if(style)
                style.remove();
            var node = content.doctype;
            var html = "<!DOCTYPE "
                + node.name
                + (node.publicId ? ' PUBLIC "' + node.publicId + '"' : '')
                + (!node.publicId && node.systemId ? ' SYSTEM' : '')
                + (node.systemId ? ' "' + node.systemId + '"' : '')
                + '>\n'
                + content.getElementsByTagName('html')[0].outerHTML;
            return html;
        } else {
            var container = this.removeMeta(this.container.cloneNode(true));
            return container.innerHTML;
        }
    }
};


Treeeditor.prototype.addDialog = function(title, saveHandler, id){
	if(id)
		var dialog = document.querySelector('#'+ id);
	else
		var dialog = document.querySelector('.treeeditor-popup');
	if(dialog){
        var topPosition = (window.innerHeight - dialog.offsetHeight)/2;
        if(topPosition <0 )
            topPosition = 0;
        dialog.style.top = (window.pageYOffset+topPosition)+'px';
    } else {
        var fragment = document.createDocumentFragment();
        var disabler = fragment.appendChild(document.createElement('DIV'));
        disabler.classList.add('treeeditor-disabler');
        disabler.addEventListener('click', this.closeDialog.bind(this), false);
        var popup = disabler.appendChild(document.createElement('DIV'));
        popup.classList.add('treeeditor-popup');
        if(id)
        	popup.setAttribute('id', id);
        var titleElement = popup.appendChild(document.createElement('DIV'));
        titleElement.classList.add('title');
        titleElement.appendChild(document.createTextNode(title || ''));
        var footer = popup.appendChild(document.createElement('DIV'));
        footer.classList.add('footer');
        this.addToolbarButton(footer, {classNames:['cancel','fa', 'fa-close'], title:'Cancel', handler: this.closeDialog});
        this.addToolbarButton(footer, {classNames:['save','fa', 'fa-check'], title:'Save', handler: saveHandler});
        var form = popup.insertBefore(document.createElement('FORM'), footer);
        document.body.appendChild(fragment);
        return form;
    }
};


Treeeditor.prototype.addDialogPage = function(dialog, name, title, search){
    var pager = dialog.querySelector('ul.pager');
    if(pager) {
         var setPage = function(page) {
            [].forEach.call(dialog.querySelectorAll('.active-page'), function(element){element.classList.remove('active-page');});
            [].forEach.call(dialog.querySelectorAll('[data-group="'+page+'"'), function(element){
                element.classList.add('active-page');
                if(element.querySelector('.search-input'))
                    element.querySelector('.search-input').focus();
            });
        };
        var header = pager.appendChild(document.createElement('LI'));
        header.setAttribute('data-group', name);
        header.appendChild(document.createTextNode(title));
        header.classList.add('page');
        header.addEventListener('click', setPage.bind(this, name), false);
        if(pager.childNodes.length == 1)
            header.classList.add('active-page');
    }
    var page = dialog.appendChild(document.createElement('DIV'));
    page.setAttribute('data-group', name);
    page.classList.add('page');
    if(!pager || (pager && pager.childNodes.length == 1))
        page.classList.add('active-page');
    if(search){
        var searchBar = page.appendChild(document.createElement('DIV'));
        searchBar.classList.add('search-bar');
        searchBar.appendChild(document.createElement('LABEL')).appendChild(document.createTextNode('Search'));
        var searchInput = searchBar.appendChild(document.createElement('INPUT'));
        searchInput.classList.add('search-input');
        searchInput.setAttribute('type', 'text');
        searchInput.addEventListener('focus', function(event){this.select();});
        searchInput.addEventListener('keyup', function(event){
            var name = event.target.value.toLowerCase();
            if(name.length){
                var elements = this.querySelectorAll('.content table tr');
                [].forEach.call(elements, function(element){
                    element.style.display = 'none';
                });
                var elements = this.querySelectorAll('.content table tr[data-name^="'+name+'"]');
                [].forEach.call(elements, function(element){
                        element.style.display = 'table-row';
                });
            } else {
                var elements = this.querySelectorAll('.content table tr');
                [].forEach.call(elements, function(element){
                        element.style.display = 'table-row';
                });
            };
        }.bind(page));
    }
    var content = page.appendChild(document.createElement('DIV'));
    content.classList.add('content');
    return content;
};

Treeeditor.prototype.dialogEdit = function()
{
    var form = document.querySelector('.treeeditor-popup form');
    if(form) {
        var attributes = form.elements.namedItem('attributes');
        for(var i = 0; i< attributes.length; i++)
        {
            if(attributes[i].value != '' && attributes[i].hasAttribute('data-name'))
                this.activeElement.setAttribute(attributes[i].getAttribute('data-name'), attributes[i].value);
            else
                this.activeElement.removeAttribute(attributes[i].getAttribute('data-name'));
        }
        var styles = form.elements.namedItem('styles');
        for(var i = 0; i< styles.length; i++) {
            if(styles[i].hasAttribute('data-name'))
                this.activeElement.style[styles[i].getAttribute('data-name')] = styles[i].value;
        }
        this.closeDialog();
        this.refreshTree();
    } else {
    	this.contextMenu.style.display = 'none';
    	var element = this.removeMeta(this.activeElement.cloneNode(true));
        var tagName = element.tagName.toLowerCase();
        var className = element.getAttribute('class') || '';
        var title = 'Edit properties: ' + tagName+'.'+className;
        var dialog = this.addDialog(title, this.dialogEdit);
        var pager = dialog.appendChild(document.createElement('UL'));
        pager.classList.add('pager');
        var attributesContent = this.addDialogPage(dialog, 'attributes', 'Attributes', true);
        var stylesContent =  this.addDialogPage(dialog, 'styles', 'Styles', true);
        var attributes = Treeeditor.tags['common'].attributes;
        var tag = this.activeElement.tagName.toLowerCase();
        if(Treeeditor.tags[tag] && Treeeditor.tags[tag].attributes){
            for(var name in Treeeditor.tags[tag].attributes)
            {
                attributes[name] = Treeeditor.tags[tag].attributes[name];
            }
        }
        var names = Object.getOwnPropertyNames(attributes).sort();
        var table = attributesContent.appendChild(document.createElement('table'));
        var colgroup = table.appendChild(document.createElement('colgroup'));
        colgroup.appendChild(document.createElement('col')).style.width = '50%';
        colgroup.appendChild(document.createElement('col'));
        names.forEach(function(name) {
            if(name != 'common'){
                var row = table.insertRow(-1);
                row.setAttribute('data-name', name);
                row.insertCell(0).appendChild(document.createTextNode(name));
                var input = row.insertCell(1).appendChild(document.createElement('INPUT'));
                input.setAttribute('type', 'text');
                input.setAttribute('name', 'attributes');
                input.setAttribute('data-name', name);
                input.setAttribute('value', element.getAttribute(name) || '');
                row.setAttribute('title', attributes[name]);
            };
        }.bind(this));

        var names = [];
        for (var name in element.style){
            if(name === name.toLowerCase() && isNaN(name) && typeof this.activeElement.style[name] != 'function' && name != 'length')
                names.push(name);
        }
        names.sort();
        var table = stylesContent.appendChild(document.createElement('table'));
        var colgroup = table.appendChild(document.createElement('colgroup'));
        colgroup.appendChild(document.createElement('col')).style.width = '50%';
        colgroup.appendChild(document.createElement('col'));
        names.forEach(function(name) {
            if(name != 'common'){
                var row = table.insertRow(-1);
                row.setAttribute('data-name', name);
                row.insertCell(0).appendChild(document.createTextNode(name));
                var input = row.insertCell(1).appendChild(document.createElement('INPUT'));
                input.setAttribute('type', 'text');
                input.setAttribute('name', 'styles');
                input.setAttribute('data-name', name);
                input.setAttribute('value', element.style[name]);
                row.setAttribute('title', attributes[name]);
            };
        }.bind(this));
        this.addDialog();
    };
};

Treeeditor.prototype.removeMeta = function(dom){
    dom.classList.remove('treeeditor-active');
    dom.classList.remove('treeeditor-editable');
    dom.classList.remove('treeeditor-hover');
    dom.removeAttribute('data-treeeditor-margin');
    dom.removeAttribute('data-treeeditor-width');
    dom.removeAttribute('data-treeeditor-display');
    dom.removeAttribute('data-treeeditor-margin-left');
    dom.removeAttribute('data-treeeditor-id');
    dom.removeAttribute('draggable');
    dom.removeAttribute('contenteditable');
    if(dom.getAttribute('class')=='')
    	dom.removeAttribute('class');
    [].forEach.call(dom.querySelectorAll('[data-treeeditor-id]'),function(element){element.removeAttribute('data-treeeditor-id');});
    [].forEach.call(dom.querySelectorAll('[contenteditable]'),function(element){element.removeAttribute('contenteditable');});
    [].forEach.call(dom.querySelectorAll('[draggable]'),function(element){element.removeAttribute('draggable');});
    [].forEach.call(dom.querySelectorAll('[class=""]'),function(element){element.removeAttribute('class');});
    [].forEach.call(dom.querySelectorAll('.treeeditor-active'),function(element){element.classList.remove('treeeditor-active');});
    [].forEach.call(dom.querySelectorAll('.treeeditor-hover'),function(element){element.classList.remove('treeeditor-hover');});
    [].forEach.call(dom.querySelectorAll('.treeeditor-over'),function(element){element.classList.remove('treeeditor-over');});
    return dom;
};


Treeeditor.prototype.dialogCode = function(event){
    var form = document.querySelector('#treeeditor-dialog-code form');
    if(form) {
        var content = form.elements.namedItem('content').value;
        if(this.activeElement){
            this.activeElement.outerHTML = content;
        } else {
            this.html(content);
        }
        this.closeDialog();
        this.refreshTree();
    } else {
    	this.contextMenu.style.display = 'none';
        var dialog = this.addDialog('Edit HTML code', this.dialogCode, 'treeeditor-dialog-code');
        var editor = dialog.appendChild(document.createElement('TEXTAREA'));
        editor.setAttribute('name', 'content');
        editor.classList.add('html-editor');
        if(this.activeElement){
        	var container = this.removeMeta(this.activeElement.cloneNode(true));
        	editor.value = container.outerHTML;
        } else
            editor.value = this.html();
        this.addDialog();
    };
};

Treeeditor.prototype.handlerOver = function(event){
    event.stopPropagation();
    event.preventDefault();
    var id = event.target.getAttribute('data-treeeditor-id');
   	var eventTarget = this.container.querySelector('[data-treeeditor-id="'+id+'"]');
    if(eventTarget && (event.type == 'scroll' || eventTarget != this.hoverElement)){
    	[].forEach.call(this.container.querySelectorAll('.treeeditor-hover'), function(element){element.classList.remove('treeeditor-hover')});
    	var id = eventTarget.getAttribute('data-treeeditor-id');
    	this.hoverElement = this.container.querySelector('[data-treeeditor-id="'+id+'"]');
    	if(this.hoverElement)
    		this.hoverElement.classList.add('treeeditor-hover');
    	if(this.tree){
    		[].forEach.call(this.tree.querySelectorAll('.treeeditor-hover'), function(element){element.classList.remove('treeeditor-hover')});
    		this.tree.querySelector('[data-treeeditor-id="'+id+'"]').parentNode.classList.add('treeeditor-hover');
    	}
    }
};

Treeeditor.prototype.handlerEnter = function(event){
	event.stopPropagation();
	if(this.hoverElement){
		this.hoverElement.classList.add('treeeditor-hover');
		if(this.tree){
			var id = this.hoverElement.getAttribute('data-treeeditor-id');
			this.tree.querySelector('[data-treeeditor-id="'+id+'"]').parentNode.classList.add('treeeditor-hover');
		}
	}
}

Treeeditor.prototype.handlerOut = function(event){
	if(event.target == this.container || event.target == this.tree || event.target == this.iframe){
    	this.container.classList.remove('treeeditor-hover');
       	[].forEach.call(this.container.querySelectorAll('.treeeditor-hover'), function(element){element.classList.remove('treeeditor-hover');});
    	if(this.tree)
    		[].forEach.call(this.tree.querySelectorAll('.treeeditor-hover'), function(element){element.classList.remove('treeeditor-hover');});;
    }
};

Treeeditor.prototype.handlerClick = function(event){
    event.stopPropagation();
    var id = event.target.getAttribute('data-treeeditor-id');
    if(id>0)
        var element = this.container.querySelector('[data-treeeditor-id="'+id+'"]');
    else
        var element = this.container;
    this.switchActive(element);
};

Treeeditor.prototype.handlerContextMenu = function(event){
	event.preventDefault();
	event.stopPropagation();
	if(!this.activeElement){
		this.activeElement = this.hoverElement;
	    var bodyPosition = document.body.getBoundingClientRect();
		if(this.iframe){
	        var iframePosition = this.iframe.getBoundingClientRect();
	       	var top = event.clientY + iframePosition.top - bodyPosition.top - 20;
	       	var left = event.clientX + iframePosition.left - bodyPosition.left - 20;
		} else {
			var left  = event.pageX - 20;
			var top  = event.pageY - 20;
		}
		this.contextMenu.style.left = left+'px';
		this.contextMenu.style.top = top+'px';
		this.contextMenu.style.display = 'block';
		this.fromContextMenu = true;
	}
};

Treeeditor.prototype.handlerContextMenuOut = function(event){
	event.stopPropagation();
	if(event.target == this.contextMenu){
		this.fromContextMenu = false;
		this.contextMenu.style.display = 'none';
		if(!this.activeEdition)
			this.activeElement = null;
		[].forEach.call(document.querySelectorAll('.active-only'), function(element){element.setAttribute('disabled',true);});
		if(this.hoverElement)
			this.hoverElement.classList.add('treeeditor-hover');
	};
};

Treeeditor.injectScript = function(frameId, name) {
	var regexp = /treeeditor/i;
	var styles = {};

    var iframe = document.getElementById(frameId).contentDocument;
    var style = iframe.createElement("style");
    style.setAttribute('id', 'treeeditor-style');
    style.appendChild(iframe.createTextNode(""));
    iframe.head.appendChild(style);
	for(var sheet in window.document.styleSheets) {
		var styleSheet = window.document.styleSheets[sheet];
		if(String(styleSheet.href).indexOf(window.document.domain) > -1) {
			for(var rule in styleSheet.cssRules) {
				var cssRule = styleSheet.cssRules[rule];
				if(typeof cssRule.selectorText != 'undefined' && cssRule.selectorText.match(regexp)){
					var selector = cssRule.selectorText;
					var str = cssRule.cssText;
					var rules = str.substring(str.lastIndexOf("{")+1,str.lastIndexOf("}"));
					Treeeditor.addCSSRule(style.sheet, selector, rules, 0);
				}
			}
		}
	}

    if(!document.getElementById(frameId).contentWindow.Node.prototype.remove){
        document.getElementById(frameId).contentWindow.Node.prototype.remove = function(){
            var parentNode = this.parentNode;
            if(parentNode)
                parentNode.removeChild(this);
            return this;
        };
    }

    if(!document.getElementById(frameId).contentWindow.Node.prototype.isInViewport){
        document.getElementById(frameId).contentWindow.Node.prototype.isInViewport = function(){
            var rect = this.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        };
    }

    iframe[name] = new Treeeditor(iframe.querySelector('body'), document.getElementById(frameId));
    return iframe[name];
};

Treeeditor.addCSSRule = function(sheet, selector, rules, index){
    if("insertRule" in sheet) {
        sheet.insertRule(selector + "{" + rules + "}", index);
    }
    else if("addRule" in sheet) {
        sheet.addRule(selector, rules, index);
    }
};

Treeeditor.prototype.switchTree = function(){
    if(!this.tree){
        if(this.iframe){
            var iframePosition = this.iframe.getBoundingClientRect();
            if(iframePosition.left>300){
                this.tree = document.body.appendChild(document.createElement('DIV'), this.iframe);
                this.tree.classList.add('treeeditor-tree');
            } else {
                this.iframe.setAttribute('data-treeditor-margin', this.iframe.style.margin);
                this.tree = this.iframe.parentNode.insertBefore(document.createElement('DIV'), this.iframe);
                this.tree.classList.add('treeeditor-tree');
            }
        } else {
        	var containerPosition = this.container.getBoundingClientRect();
        	var bodyPosition = document.body.getBoundingClientRect();
        	this.tree = document.body.appendChild(document.createElement('DIV'), this.container);
            this.tree.classList.add('treeeditor-tree');
            if(containerPosition.left<300){
                this.container.setAttribute('data-treeeditor-width', this.container.style.width);
	            this.container.setAttribute('data-treeeditor-display', this.container.style.display);
	            this.container.setAttribute('data-treeeditor-margin-left', this.container.style.marginLeft);

	            this.container.style.width = containerPosition.width + 'px';
	            this.container.style.display = 'inline-block';
                this.container.style.marginLeft = 304-containerPosition.left + bodyPosition.left+ 'px';

            }
        }
        this.tree.addEventListener('click', this.handlerClick.bind(this), true);
        this.tree.addEventListener('mouseenter', this.handlerEnter.bind(this), true);
        this.tree.addEventListener('mouseout', this.handlerOut.bind(this), true);
        this.tree.addEventListener('mouseover', this.handlerOver.bind(this), false);
        this.refreshTree();
        if(this.activeElement){
	        var id = this.activeElement.getAttribute('data-treeeditor-id');
	        var treeElement = this.tree.querySelector('[data-treeeditor-id="'+id+'"]');
	        treeElement.parentNode.classList.add('treeeditor-active');
	        if(!treeElement.isInViewport())
	            treeElement.scrollIntoView(true);
        }
    } else {
        if(this.iframe){
            if(this.iframe.hasAttribute('data-treeditor-margin')){
                this.container.style.margin = this.container.getAttribute('data-treeeditor-margin');
                this.container.removeAttribute('data-treeeditor-margin');
            }
        } else {
             this.container.style.width = this.container.getAttribute('data-treeeditor-width');
             this.container.removeAttribute('data-treeeditor-width');

             this.container.style.display =  this.container.getAttribute('data-treeeditor-display');
             this.container.removeAttribute('data-treeeditor-display');

             this.container.style.marginLeft = this.container.getAttribute('data-treeeditor-margin-left');
             this.container.removeAttribute('data-treeeditor-margin-left');
        }
        this.tree.remove();
        delete this.tree;
    }
};

Treeeditor.prototype.getClassNames = function(element){
    var result = '';
    var reg = /treeeditor.?/;
    [].forEach.call(element.classList, function(name){
        if(!reg.test(name))
            result = result+'.'+name;
    });
    return result;
};

Treeeditor.prototype.getElementChildren = function(element){
    var tagName = element.tagName || '';
    var className = this.getClassNames(element);
    if(element.hasAttribute('id'))
        var id = '#'+ element.getAttribute('id');
    else
        var id = '';
    var container = document.createElement('DIV');
    container.classList.add('node');
    if(element == this.activeElement)
    	container.classList.add('treeeditor-active');
    var header = container.appendChild(document.createElement('DIV'));
    header.classList.add('header');
    header.appendChild(document.createTextNode(tagName.toLowerCase()+id+className));
    var id = element.getAttribute('data-treeeditor-id');
    header.setAttribute('data-treeeditor-id', id);
    if(element.children.length){
        var children = container.appendChild(document.createElement('DIV'));
        children.classList.add('children');
        [].forEach.call(element.children, function(child){
            children.appendChild(this.getElementChildren(child));
        }.bind(this));
    }
    return container;
};

Treeeditor.prototype.refreshTree = function(){
    this.container.setAttribute('data-treeeditor-id', 0);
    var treeeditorId = 1;
    var elements = this.container.querySelectorAll('*');
    [].forEach.call(elements, function(element){
        element.setAttribute('data-treeeditor-id', treeeditorId++);
    }.bind(this));
    if(this.tree){
        if( this.tree.firstChild)
            this.tree.firstChild.remove();
        this.tree.appendChild(this.getElementChildren(this.container));
        if(this.iframe){
            var height = this.iframe.clientHeight;
            var iframePosition = this.iframe.getBoundingClientRect();
            if(iframePosition.left>300 && this.tree.style.float != 'left'){
                this.tree.style.position = 'absolute';
                var bodyPosition = document.body.getBoundingClientRect();
                var topPosition = iframePosition.top - bodyPosition.top;
                this.tree.style.top = topPosition +'px';
                var leftPosition = iframePosition.left - bodyPosition.left - 300;
                this.tree.style.left = leftPosition +'px';
            } else {
                this.iframe.style.marginLeft = '0';
                this.tree.style.left = '0px';
                this.tree.style.float = 'left';
            }
        } else {
            var height = this.container.clientHeight-2;
            var bodyPosition = document.body.getBoundingClientRect();
            var containerPosition = this.container.getBoundingClientRect();
            this.tree.style.display = 'inline-block';
            var topPosition = containerPosition.top - bodyPosition.top;
            var leftPosition = containerPosition.left - bodyPosition.left;
            if(leftPosition>304){
                this.tree.style.left = (leftPosition-304) +'px';
            } else {
                this.tree.style.left = '0px';
                this.container.style.width = this.container.clientWidth+'px';
                this.container.style.display = 'inline-block';
                this.container.style.marginLeft = parseInt(containerPosition) + 300-leftPosition + 'px';
            }
            this.tree.style.position = 'absolute';
            this.tree.style.top = topPosition +'px';

        }
        this.tree.style.height = height + 'px';
        if(this.activeElement)
            this.tree.querySelector('[data-treeeditor-id="'+this.activeElement.getAttribute('data-treeeditor-id')+'"]').classList.add('treeeditor-active');
    }
};



Treeeditor.prototype.resize = function(){
    var bodyPosition = document.body.getBoundingClientRect();
    if(this.iframe) {
        var iframePosition = this.iframe.getBoundingClientRect();
        var topPosition = iframePosition.top - bodyPosition.top;
        var leftPosition = iframePosition.left - bodyPosition.left;
    } else {
        var containerPosition = this.container.getBoundingClientRect();
        var topPosition =  containerPosition.top - bodyPosition.top;
        var leftPosition = containerPosition.left - bodyPosition.left;
    }
    this.mainToolbar.style.top = topPosition-35 +'px';
    this.mainToolbar.style.left = leftPosition +'px';
    this.refreshTree();
}


Treeeditor.groups = {
	'custom': {
		name: 'Predefined elements'
	},
	'text': {
		name: 'Text'
	},
	'headers': {
		name: 'Headers'
	},
	'lists': {
		name: 'Lists'
	},
	'container': {
		name : 'Containers'
	},
	'forms' : {
		name : 'Forms'
	},
	'table' : {
		name : 'Tables'
	},
	'media' : {
		name : 'Media'
	},
	'link' : {
		name: 'Links'
	},
	'format': {
		name: 'Format'
	},
	'document': {
		name: 'Document parts'
	},
};

Treeeditor.tags = {
    common : {
        attributes: {
            accesskey: 'Specifies a shortcut key to activate/focus an element',
            class: 'Specifies one or more  for an element (refers to a class in a style sheet)',
            contenteditable: 'Specifies whether the content of an element is editable or not',
            contextmenu: 'Specifies a context menu for an element. The context menu appears when a user right-clicks on the element',
            dir: 'Specifies the text direction for the content in an element',
            draggable: 'Specifies whether an element is draggable or not',
            dropzone: 'Specifies whether the dragged data is copied, moved, or linked, when dropped',
            hidden: 'Specifies that an element is not yet, or is no longer, relevant',
            id: 'Specifies a unique id for an element',
            lang: 'Specifies the language of the element\'s content',
            spellcheck: 'Specifies whether the element is to have its spelling and grammar checked or not',
            tabindex : 'Specifies the tabbing order of an element',
            title: 'Specifies extra information about an element',
            translate: 'Specifies whether the content of an element should be translated or not',
        }
    },
    a: {
        description : 'The <a> tag defines a hyperlink, which is used to link from one page to another.',
        attributes: {
            download: 'Specifies that the target will be downloaded when a user clicks on the hyperlink',
            href: 'Specifies the URL of the page the link goes to',
            hreflang: 'Specifies the language of the linked document',
            media: 'Specifies what media/device the linked document is optimized for',
            target: 'Specifies where to open the linked document',
            type: 'Specifies the media type of the linked document'
        },
        category: 'link'
    },
    abbr: {
        description: 'The <abbr> tag defines an abbreviation or an acronym, like "Mr.", "Dec.", "ASAP", "ATM".',
        category: 'text',
        next: 'edit'
    },
    address : {
        description: 'The <address> tag defines the contact information for the author/owner of a document or an articl',
        category: 'text',
        next: 'edit'
    },
    area : {
        description: 'The <area> tag defines an area inside an image-map (an image-map is an image with clickable areas).',
        attributes: {
            alt: 'Specifies an alternate text for the area. Required if the href attribute is present',
            coords: 'Specifies the coordinates of the area',
            download: 'Specifies that the target will be downloaded when a user clicks on the hyperlink',
            href: 'Specifies the hyperlink target for the area',
            hreflang: 'Specifies the language of the target URL',
            media: 'Specifies what media/device the target URL is optimized for',
            rel: 'Specifies the relationship between the current document and the target URL',
            shape: 'Specifies the shape of the area',
            target: 'Specifies where to open the target URL',
            type: 'Specifies the media type of the target URL'
        },
        category: 'media',
        parent: ['map']
    },
    article: {
        description: 'The <article> tag specifies independent, self-contained content.',
        category: 'container',
        next: 'build'
    },
    aside: {
        description: 'The <aside> tag defines some content aside from the content it is placed in.',
        category: 'container',
        next: 'build'
    },
    audio: {
        description: 'The <audio> tag defines sound, such as music or other audio streams.',
        attributes: {
            autoplay: 'Specifies that the audio will start playing as soon as it is ready',
            controls: 'Specifies that audio controls should be displayed (such as a play/pause button etc)',
            loop: 'Specifies that the audio will start over again, every time it is finished',
            muted: 'Specifies that the audio output should be muted',
            preload: 'Specifies if and how the author thinks the audio should be loaded when the page loads',
            src: 'Specifies the URL of the audio file'
        },
        category: 'media'
    },
    b: {
        description: 'The <b> tag specifies bold text.',
        category: 'text',
        next: 'edit'
    },
    base: {
        description: 'The <base> tag specifies the base URL/target for all relative URLs in a document.',
        attributes: {
            href: 'Specifies the base URL for all relative URLs in the page',
            target: 'Specifies the default target for all hyperlinks and forms in the page',
        },
        category: 'link',
    },
    bdi: {
        description: 'The <bdi> tag isolates a part of text that might be formatted in a different direction from other text outside it.',
        category: 'text',
        next: 'edit',
    },
    bdo: {
        description: 'The <bdo> tag is used to override the current text direction.',
        attributes: {
            dir : 'Required. Specifies the text direction of the text inside the <bdo> element',
        },
        category: 'text',
        next: 'edit',
    },
    blockquote: {
        description: 'The <blockquote> tag specifies a section that is quoted from another source.',
        attributes: {
            cite: 'Specifies the source of the quotation',
        },
        category: 'text',
        next: 'edit',
    },
    body: {
        description: 'The <body> element contains all the contents of an HTML document, such as text, hyperlinks, images, tables, lists, etc.',
        category: 'document',
        next: 'build',
        parent: ['html'],
    },
    br :{
        description: 'The <br> tag inserts a single line break.',
        category: 'format',
        next: 'none',
    },
    button: {
        description: 'The <button> tag defines a clickable button.',
        attributes: {
            autofocus: 'Specifies that a button should automatically get focus when the page loads',
            disabled: 'Specifies that a button should be disabled',
            form: 'Specifies one or more forms the button belongs to',
            formaction: 'Specifies where to send the form-data when a form is submitted. Only for type="submit"',
            formenctype: 'Specifies how form-data should be encoded before sending it to a server. Only for type="submit"',
            formmethod: 'Specifies how to send the form-data (which HTTP method to use). Only for type="submit"',
            formnovalidate: 'Specifies that the form-data should not be validated on submission. Only for type="submit"',
            formtarget: 'Specifies where to display the response after submitting the form. Only for type="submit"',
            name: 'Specifies a name for the button',
            type: 'Specifies the type of button',
            value: 'Specifies an initial value for the button',
        },
        category: 'forms',
    },
    canvas: {
        description: 'The <canvas> tag is used to draw graphics, on the fly, via scripting (usually JavaScript)',
        attributes: {
            height: 'Specifies the height of the canvas',
            width: 'Specifies the width of the canvas',
        },
        category: 'media',
    },
    caption: {
        description: 'The <caption> tag defines a table caption.',
        category: 'table',
        parent: ['table'],
        next: 'edit',
    },
    cite: {
        description: 'The <cite> tag defines the title of a work (e.g. a book, a song, a movie, a TV show, a painting, a sculpture, etc.).',
        category: 'text',
        next: 'edit',
    },
    code: {
        description: 'The <code> tag is a phrase tag. It defines a piece of computer code.',
        category: 'text',
        next: 'edit',
    },
    col: {
        description: 'The <col> tag specifies column properties for each column within a <colgroup> element.',
        attributes: {
            span: 'Specifies the number of columns a <col> element should span',
        },
        category: 'table',
        parent: ['colgroup'],
        next: 'buil',
    },
    colgroup: {
        description: 'The <colgroup> tag specifies a group of one or more columns in a table for formatting.',
        attributes: {
            span: 'Specifies the number of columns a column group should span',
        },
        category: 'table',
        parent: ['table'],
        children: ['col'],
        next: 'build',
    },
    datalist: {
        description: 'The <datalist> tag is used to provide an "autocomplete" feature on <input> elements.',
        category: 'forms',
        next: 'build',
        children: ['option'],
    },
    dd: {
        description: 'The <dd> tag is used to describe a term/name in a description list.',
        category: 'lists',
        next: 'edit',
        parent: ['dl']
    },
    del: {
        description: 'The <del> tag defines text that has been deleted from a document.',
        attributes: {
            cite: 'Specifies a URL to a document that explains the reason why the text was deleted',
            datetime: 'Specifies the date and time of when the text was deleted, format YYYY-MM-DDThh:mm:ssTZD',
        },
        category: 'text',
        next: 'edit',
    },
    details: {
        description: 'The <details> tag specifies additional details that the user can view or hide on demand.',
        attributes: {
            open: 'Specifies that the details should be visible (open) to the user'
        },
        category: 'text',
        next: 'edit',
    },
    dfn: {
        description: 'The <dfn> tag represents the defining instance of a term in HTML.',
        category: 'text',
        next: 'edit',
    },
    dialog: {
        description: 'The <dialog> tag defines a dialog box or window.',
        attributes: {
            open: 'Specifies that the dialog element is active and that the user can interact with it',
        },
        category: 'container',
        next: 'build',
    },
    div: {
        description: 'The <div> tag defines a division or a section in an HTML document.',
        category: 'container',
        next: 'build',
    },
    dl: {
        description: 'The <dl> tag defines a description list.',
        category: 'lists',
        next: 'build',
        children: ['dt', 'dd'],
    },
    dt: {
        description: 'The <dt> tag defines a term/name in a description list.',
        category: 'lists',
        next: 'edit',
        parent: ['dl']
    },
    em: {
        description: 'The <em> tag is a phrase tag. It renders as emphasized text.',
        category: 'text',
        next: 'edit',
    },
    embed: {
        description: 'The <embed> tag defines a container for an external application or interactive content (a plug-in).',
        attributes: {
            height: 'Specifies the height of the embedded content',
            src: 'Specifies the address of the external file to embed',
            type: 'Specifies the media type of the embedded content',
            width: 'Specifies the width of the embedded content',
        },
        category: 'media',
    },
    fieldset: {
        description: 'The <fieldset> tag is used to group related elements in a form.',
        attributes: {
            disabled: 'Specifies that a group of related form elements should be disabled',
            form: 'Specifies one or more forms the fieldset belongs to',
            name: 'Specifies a name for the fieldset',
        },
        category: 'forms',
        next: 'edit',
    },
    figcaption: {
        description: 'The <figcaption> tag defines a caption for a <figure> element.',
        category: 'container',
        next: 'edit',
        parent: ['figure'],
    },
    figure: {
        description: 'The <figure> tag specifies self-contained content, like illustrations, diagrams, photos, code listings, etc.',
        category: 'container',
        next: 'build',
    },
    footer: {
        description: 'The <footer> tag defines a footer for a document or section.',
        category: 'container',
        next: 'build',
    },
    form: {
        description: 'The <form> tag is used to create an HTML form for user input.',
        attributes: {
            'accept-charset': 'Specifies the character encodings that are to be used for the form submission',
            action: 'Specifies where to send the form-data when a form is submitted',
            autocomplete: 'Specifies whether a form should have autocomplete on or off',
            enctype: 'Specifies how the form-data should be encoded when submitting it to the server (only for method="post")',
            method: 'Specifies the HTTP method to use when sending form-data',
            name: 'Specifies the name of a form',
            novalidate: 'Specifies that the form should not be validated when submitted',
            target: 'Specifies where to display the response that is received after submitting the form',
        },
        category: 'forms',
        next: 'build',
    },
    h1: {
        description: 'The <h1> to <h6> tags are used to define HTML headings.',
        category: 'headers',
        next: 'edit',
    },
    h2: {
        description: 'The <h1> to <h6> tags are used to define HTML headings.',
        category: 'headers',
        next: 'edit',
    },
    h3: {
        description: 'The <h1> to <h6> tags are used to define HTML headings.',
        category: 'headers',
        next: 'edit',
    },
    h4: {
        description: 'The <h1> to <h6> tags are used to define HTML headings.',
        category: 'headers',
        next: 'edit',
    },
    h5: {
        description: 'The <h1> to <h6> tags are used to define HTML headings.',
        category: 'headers',
        next: 'edit',
    },
    h6: {
        description: 'The <h1> to <h6> tags are used to define HTML headings.',
        category: 'headers',
        next: 'edit',
    },
    head: {
        description: 'The <head> element is a container for all the head elements.',
        category: 'document',
        next: 'build',
        parent: ['html'],
        children: ['title','style','base','link','meta','script','noscript'],
    },
    header: {
        description: 'The <header> element represents a container for introductory content or a set of navigational links.',
        category: 'container',
        next: 'build',
    },
    hr: {
        description: 'The <hr> tag defines a thematic break in an HTML page (e.g. a shift of topic).',
        category: 'format',
        next: 'none',
    },
    html: {
        description: 'The <html> tag represents the root of an HTML document.',
        attributes: {
            manifest: 'Specifies the address of the document\'s cache manifest (for offline browsing)',
            xmlns: 'Specifies the XML namespace attribute (If you need your content to conform to XHTML)',
        },
        category: 'document',
        next: 'build',
        children: ['head', 'body'],
        parent: ['']
    },
    i: {
        description: 'The <i> tag defines a part of text in an alternate voice or mood. The content of the <i> tag is usually displayed in italic.',
        category: 'text',
        next: 'edit',
    },
    iframe: {
        description: 'The <iframe> tag specifies an inline frame.',
        attributes: {
            height: 'Specifies the height of an <iframe>',
            name: 'Specifies the name of an <iframe>',
            sandbox: 'Enables an extra set of restrictions for the content in an <iframe>',
            src: 'Specifies the address of the document to embed in the <iframe>',
            srcdoc: 'Specifies the HTML content of the page to show in the <iframe>',
            width: 'Specifies the width of an <iframe>',
        },
        category: 'container',
    },
    img: {
        description: 'The <img> tag defines an image in an HTML page.',
        attributes: {
            alt: 'Specifies an alternate text for an image',
            crossorigin: 'Allow images from third-party sites that allow cross-origin access to be used with canvas',
            height: 'Specifies the height of an image',
            ismap: 'Specifies an image as a server-side image-map',
            longdesc: 'Specifies a URL to a detailed description of an image',
            src: 'Specifies the URL of an image',
            usemap: 'Specifies an image as a client-side image-map',
            width: 'Specifies the width of an image',
        },
        children: [],
        category: 'media',
    },
    input: {
        description: 'The <input> tag specifies an input field where the user can enter data.',
        attributes: {
            accept: 'Specifies the types of files that the server accepts (only for type="file")',
            alt: 'Specifies an alternate text for images (only for type="image")',
            autocomplete: 'Specifies whether an <input> element should have autocomplete enabled',
            autofocus: 'Specifies that an <input> element should automatically get focus when the page loads',
            checked: 'Specifies that an <input> element should be pre-selected when the page loads (for type="checkbox" or type="radio")',
            disabled: 'Specifies that an <input> element should be disabled',
            form: 'Specifies one or more forms the <input> element belongs to',
            formaction: 'Specifies the URL of the file that will process the input control when the form is submitted (for type="submit" and type="image")',
            formenctype: 'Specifies how the form-data should be encoded when submitting it to the server (for type="submit" and type="image")',
            formmethod: 'Defines the HTTP method for sending data to the action URL (for type="submit" and type="image")',
            formnovalidate: 'Defines that form elements should not be validated when submitted',
            formtarget: 'Specifies where to display the response that is received after submitting the form (for type="submit" and type="image")',
            height: 'Specifies the height of an <input> element (only for type="image")',
            list: 'Refers to a <datalist> element that contains pre-defined options for an <input> element',
            max: 'Specifies the maximum value for an <input> element',
            maxlength: 'Specifies the maximum number of characters allowed in an <input> element',
            min: 'Specifies a minimum value for an <input> element',
            multiple: 'Specifies that a user can enter more than one value in an <input> element',
            name: 'Specifies the name of an <input> element',
            pattern: 'Specifies a regular expression that an <input> element\'s value is checked against',
            placeholder: 'Specifies a short hint that describes the expected value of an <input> element',
            readonly: 'Specifies that an input field is read-only',
            required: 'Specifies that an input field must be filled out before submitting the form',
            size: 'Specifies the width, in characters, of an <input> element',
            src: 'Specifies the URL of the image to use as a submit button (only for type="image")',
            step: 'Specifies the legal number intervals for an input field',
            type: 'Specifies the type <input> element to display',
            value: 'Specifies the value of an <input> element',
            width: 'Specifies the width of an <input> element (only for type="image")',
        },
        category: 'forms',
        children: [],
    },
    ins: {
        description: 'he <ins> tag defines a text that has been inserted into a document.',
        attributes: {
            cite: 'Specifies a URL to a document that explains the reason why the text was inserted/changed',
            datetime: 'Specifies the date and time when the text was inserted/changed, format YYYY-MM-DDThh:mm:ssTZD',
        },
        category: 'text',
        next: 'edit',
    },
    kbd: {
        description: 'The <kbd> tag is a phrase tag. It defines keyboard input.',
        category: 'text',
        next: 'edit',
    },
    keygen:{
        description: 'The <keygen> tag specifies a key-pair generator field used for forms.',
        attributes: {
            autofocus: 'Specifies that a <keygen> element should automatically get focus when the page loads',
            challenge: 'Specifies that the value of the <keygen> element should be challenged when submitted',
            disabled: 'Specifies that a <keygen> element should be disabled',
            form: 'Specifies one or more forms the <keygen> element belongs to',
            keytype: 'Specifies the security algorithm of the key',
            name: 'Defines a name for the <keygen> element',
        },
        category: 'forms',
    },
    label: {
        description: 'The <label> tag defines a label for an <input> element.',
        attributes: {
            'for': 'Specifies which form element a label is bound to',
            form: 'Specifies one or more forms the label belongs to',
        },
        category: 'forms',
        next: 'edit',
    },
    legend: {
        description: 'The <legend> tag defines a caption for the <fieldset> element.',
        category: 'forms',
        next: 'edit',
        parent: ['fieldset'],
    },
    li: {
        description: 'The <li> tag defines a list item.',
        attributes: {
            value: 'Specifies the value of a list item. The following list items will increment from that number (only for <ol> lists)',
        },
        category: 'lists',
        next: 'edit',
        parent: ['ul', 'ol'],
    },
    link: {
        description: 'The <link> tag defines a link between a document and an external resource.',
        attributes: {
            crossorigin: 'Specifies how the element handles cross-origin requests',
            href: 'Specifies the location of the linked document',
            hreflang: 'Specifies the language of the text in the linked document',
            media: 'Specifies on what device the linked document will be displayed',
            rel: 'Specifies the relationship between the current document and the linked document',
            sizes: 'Specifies the size of the linked resource. Only for rel="icon"',
            type: 'Specifies the media type of the linked document',
        },
        category: 'link',
    },
    main: {
        description: 'The <main> tag specifies the main content of a document.',
        category: 'container',
        next: 'build',
    },
    map: {
        description: 'The <map> tag is used to define a client-side image-map. An image-map is an image with clickable areas.',
        attributes: {
            name: 'Required. Specifies the name of an image-map',
        },
        category: 'media',
        next: 'edit',
        children: ['area'],
    },
    mark: {
        description: 'The <mark> tag defines marked text.',
        category: 'text',
        next: 'edit',
    },
    menu: {
        description: 'The <menu> tag defines a list/menu of commands.',
        attributes: {
            label: 'Specifies a visible label for the menu',
            type: 'Specifies which type of menu to display',
        },
        category: 'container',
        next: 'build',
        children: ['menu', 'menuitem'],
    },
    menuitem : {
        description: 'The <menuitem> tag defines a command/menu item that the user can invoke from a popup menu.',
        attributes: {
            checked: 'Specifies that the command/menu item should be checked when the page loads. Only for type="radio" or type="checkbox"',
            command: 'Specifies the ID of a separate element or indicating a command to be invoked indirectly',
            'default': 'Marks the command/menu item as being a default command',
            disabled: 'Specifies that the command/menu item should be disabled',
            icon: 'Specifies an icon for the command/menu item',
            label: 'Required. Specifies the name of the command/menu item, as shown to the user',
            radiogroup: 'Specifies the name of the group of commands that will be toggled when the command/menu item itself is toggled. Only for type="radio"',
            type : 'Specifies the type of command/menu item. Default is "command"',
        },
        category: 'container',
        next: 'build',
        children: ['menu'],
    },
    meta: {
        description: 'The <meta> tag provides metadata about the HTML document.',
        attributes: {
            charset: 'Specifies the character encoding for the HTML document',
            content: 'Gives the value associated with the http-equiv or name attribute',
            'http-equiv': 'Provides an HTTP header for the information/value of the content attribute',
            name: 'Specifies a name for the metadata',
        },
        category: 'document',
        next: 'none',
        parent: ['head'],
    },
    meter: {
        description: 'The <meter> tag defines a scalar measurement within a known range, or a fractional value.',
        attributes: {
            form: 'Specifies one or more forms the <meter> element belongs to',
            high: 'Specifies the range that is considered to be a high value',
            low: 'Specifies the range that is considered to be a low value',
            max: 'Specifies the maximum value of the range',
            min: 'Specifies the minimum value of the range',
            optimum: 'Specifies what value is the optimal value for the gauge',
            value : 'Required. Specifies the current value of the gauge',
        },
        category: 'forms',
    },
    nav: {
        description: 'The <nav> tag defines a set of navigation links.',
        category: 'container',
        next: 'build',
    },
    noscript: {
        description: 'The <noscript> tag defines an alternate content for users that have disabled scripts in their browser or have a browser that doesn\'t support script.',
        category: 'document',
        next: 'build',
        parent: ['head']
    },
    object: {
        description: 'The <object> tag defines an embedded object within an HTML document.',
        attributes: {
            data: 'Specifies the URL of the resource to be used by the object',
            form: 'Specifies one or more forms the object belongs to',
            height: 'Specifies the height of the object',
            name: 'Specifies a name for the object',
            type: 'Specifies the media type of data specified in the data attribute',
            usemap: 'Specifies the name of a client-side image map to be used with the object',
            width: 'Specifies the width of the object',
        },
        category: 'media',
    },
    ol: {
        description: 'The <ol> tag defines an ordered list. An ordered list can be numerical or alphabetical.',
        attributes: {
            reversed: 'Specifies that the list order should be descending (9,8,7...)',
            start: 'Specifies the start value of an ordered list',
            type: 'Specifies the kind of marker to use in the list',
        },
        category: 'lists',
        next: 'build',
        children: ['li'],
    },
    optgroup : {
        description: 'The <optgroup> is used to group related options in a drop-down list.',
        attributes: {
            disabled: 'Specifies that an option-group should be disabled',
            label: 'Specifies a label for an option-group',
        },
        category: 'forms',
        next: 'build',
        parent: ['select'],
        children: ['option'],
    },
    option: {
        description: 'The <option> tag defines an option in a select list.',
        attributes: {
            disabled: 'Specifies that an option should be disabled',
            label: 'Specifies a shorter label for an option',
            selected: 'Specifies that an option should be pre-selected when the page loads',
            value: 'Specifies the value to be sent to a server',
        },
        category: 'forms',
        next: 'edit',
        parent: ['select', 'datalist'],
    },
    output: {
        description: 'The <output> tag represents the result of a calculation (like one performed by a script).',
        attributes: {
            'for': 'Specifies the relationship between the result of the calculation, and the elements used in the calculation',
            form: 'Specifies one or more forms the output element belongs to',
            name: 'Specifies a name for the output element',
        },
        category: 'text',
        next: 'edit',
    },
    p: {
        description: 'The <p> tag defines a paragraph.',
        category: 'text',
        next: 'edit',
    },
    param: {
        description: 'The <param> tag is used to define parameters for plugins embedded with an <object> element.',
        attributes: {
            name: 'Specifies the name of a parameter',
            value: 'Specifies the value of the parametert',
        },
        category: 'media',
        parent: ['object'],
    },
    pre: {
        description: 'The <pre> tag defines preformatted text.',
        category: 'text',
        next: 'edit',
    },
    progress: {
        description: 'The <progress> tag represents the progress of a task. ',
        attributes: {
            max: 'Specifies how much work the task requires in total',
            value: 'Specifies how much of the task has been completed',
        },
        category: 'text',
    },
    q: {
        description: 'The <q> tag defines a short quotation.',
        attributes: {
            cite: 'Specifies the source URL of the quote',
        },
        category: 'text',
        next: 'edit',
    },
    rp: {
        description: 'The <rp> tag can be used to provide parentheses around a ruby text, to be shown by browsers that do not support ruby annotations.',
        category: 'text',
        next: 'edit',
    },
    rt: {
        description: 'The <rt> tag defines an explanation or pronunciation of characters (for East Asian typography) in a ruby annotation.',
        category: 'text',
        next: 'edit',
    },
    ruby: {
        description: 'The <ruby> tag specifies a ruby annotation.',
        category: 'text',
        next: 'edit',
    },
    s: {
        description: 'The <s> element is use to define text that is no longer correct.',
        category: 'text',
        next: 'edit',
    },
    samp: {
        description: 'The <samp> tag is a phrase tag. It defines sample output from a computer program.',
        category: 'text',
        next: 'edit',
    },
    scritp: {
        description: 'The <script> tag is used to define a client-side script (JavaScript).',
        attributes: {
            async: 'Specifies that the script is executed asynchronously (only for external scripts)',
            charset: 'Specifies the character encoding used in an external script file',
            defer: 'Specifies that the script is executed when the page has finished parsing (only for external scripts)',
            src: 'Specifies the URL of an external script file',
            type: 'Specifies the media type of the script',
        },
        category: 'document',
        next: 'edit',
    },
    section:{
        description: 'The <section> tag defines sections in a document, such as chapters, headers, footers, or any other sections of the document',
        category: 'container',
        next: 'build',
    },
    select: {
        description: 'The <select> element is used to create a drop-down list.',
        attributes: {
            autofocus: 'Specifies that the drop-down list should automatically get focus when the page loads',
            disabled: 'Specifies that a drop-down list should be disabled',
            form: 'Defines one or more forms the select field belongs to',
            multiple: 'Specifies that multiple options can be selected at once',
            name: 'Defines a name for the drop-down list',
            required: 'Specifies that the user is required to select a value before submitting the form',
            size: 'Defines the number of visible options in a drop-down list',
        },
        category: 'forms',
        next: 'build',
    },
    small: {
        description: 'The <small> tag defines smaller text (and other side comments).',
        category: 'text',
        next: 'edit',
    },
    source: {
        description: 'The <source> tag is used to specify multiple media resources for media elements, such as <video> and <audio>.',
        attributes: {
            media: 'Specifies the type of media resource',
            src: 'Specifies the URL of the media file',
            type: 'Specifies the media type of the media resource',
        },
        category: 'media',
        parent:['video', 'audio'],
    },
    span: {
        description: 'The <span> tag is used to group inline-elements in a document.',
        category: 'text',
        next: 'edit',
    },
    strong: {
        description: 'The <strong> tag is a phrase tag. It defines important text.',
        category: 'text',
        next: 'edit',
    },
    style: {
        description: 'The <style> tag is used to define style information for an HTML document.',
        attributes: {
            media: 'Specifies what media/device the media resource is optimized for',
            scoped: 'Specifies that the styles only apply to this element\'s parent element and that element\'s child elements',
            type: 'Specifies the media type of the <style> tag',
        },
        category: 'document',
        next: 'edit',
    },
    sub: {
        description: 'The <sub> tag defines subscript text.',
        category: 'text',
        next: 'edit',
    },
    summary: {
        description: 'The <summary> tag defines a visible heading for the <details> element.',
        category: 'text',
        next: 'edit',
        parent: ['details'],
    },
    sup: {
        description: 'The <sup> tag defines superscript text.',
        category: 'text',
        next: 'edit',
    },
    table: {
        description: 'The <table> tag defines an HTML table.',
        attributes: {
            border: 'Specifies whether or not the table is being used for layout purposes',
            sortable: 'Specifies that the table should be sortable',
        },
        children: ['colgroup','caption','thead','tbody','tfoot','tr'],
        category: 'table',
        next: 'build',
    },
    tbody: {
        description: 'The <tbody> tag is used to group the body content in an HTML table.',
        children: ['tr'],
        parent: ['table'],
        category: 'table',
        next: 'build',
    },
    td: {
        description: 'The <td> tag defines a standard cell in an HTML table.',
        attributes: {
            colspan: 'Specifies the number of columns a cell should span',
            headers: 'Specifies one or more header cells a cell is related to',
            rowspan: 'Sets the number of rows a cell should span',
        },
        category: 'table',
        parent: ['tr'],
        next: 'edit',
    },
    textarea : {
        description: 'The <textarea> tag defines a multi-line text input control.',
        attributes: {
            autofocus: 'Specifies that a text area should automatically get focus when the page loads',
            cols: 'Specifies the visible width of a text area',
            disabled: 'Specifies that a text area should be disabled',
            form: 'Specifies one or more forms the text area belongs to',
            maxlength: 'Specifies the maximum number of characters allowed in the text area',
            name: 'Specifies a name for a text area',
            placeholder: 'Specifies a short hint that describes the expected value of a text area',
            readonly: 'Specifies that a text area should be read-only',
            required: 'Specifies that a text area is required/must be filled out',
            rows: 'Specifies the visible number of lines in a text area',
            wrap: 'Specifies how the text in a text area is to be wrapped when submitted in a form',
        },
        category: 'forms',
    },
    tfoot: {
        description: 'The <tfoot> tag is used to group footer content in an HTML table.',
        children: ['tr'],
        parent: ['table'],
        category: 'table',
        next: 'build',
    },
    th: {
        description: 'The <th> tag defines a header cell in an HTML table.',
        attributes: {
            colspan: 'Specifies the number of columns a cell should span',
            headers: 'Specifies one or more header cells a cell is related to',
            rowspan: 'Sets the number of rows a cell should span',
            scope: 'Specifies whether a header cell is a header for a column, row, or group of columns or rows',
            sorted: 'Defines the sort direction of a column',
        },
        category: 'table',
        next: 'edit',
        parent: ['tr'],
    },
    thead: {
        description: 'The <thead> tag is used to group header content in an HTML table.',
        category: 'table',
        next: 'build',
        parent: ['table'],
        children: ['tr'],
    },
    time: {
        description: 'The <time> tag defines a human-readable date/time.',
        attributes: {
            datetime: 'Represent a machine-readable date/time of the <time> element',
        },
        category: 'format',
    },
    title: {
        description: 'The <title> tag is required in all HTML documents and it defines the title of the document.',
        category: 'document',
        next: 'edit',
        parent: ['head'],
    },
    tr: {
        description: 'The <tr> tag defines a row in an HTML table.',
        parent: ['table', 'thead', 'tbody', 'ftoot'],
        children : ['th', 'td'],
        category: 'table',
        next: 'build',
    },
    track: {
        description: 'The <track> tag specifies text tracks for media elements (<audio> and <video>).',
        attributes: {
            'default': 'Specifies that the track is to be enabled if the user\'s preferences do not indicate that another track would be more appropriate',
            kind: 'Specifies the kind of text track',
            label: 'Specifies the title of the text track',
            src: 'Required. Specifies the URL of the track file',
            srclang: 'Specifies the language of the track text data (required if kind="subtitles")',
        },
        category: 'media',
    },
    u: {
        description: 'The <u> tag represents some text that should be stylistically different from normal text, such as misspelled words or proper nouns in Chinese.',
        category: 'text',
        next: 'edit',
    },
    ul: {
        description: 'The <ul> tag defines an unordered (bulleted) list.',
        children: ['li'],
        category: 'lists',
        next: 'build',
    },
    'var': {
        description: 'The <var> tag is a phrase tag. It defines a variable.',
        category: 'text',
        next: 'edit',
    },
    video: {
        description: 'The <video> tag specifies video, such as a movie clip or other video streams.',
        attributes: {
            autoplay: 'Specifies that the video will start playing as soon as it is ready',
            controls: 'Specifies that video controls should be displayed (such as a play/pause button etc)',
            height: 'Sets the height of the video player',
            loop: 'Specifies that the video will start over again, every time it is finished',
            muted: 'Specifies that the audio output of the video should be muted',
            poster: 'Specifies an image to be shown while the video is downloading, or until the user hits the play button',
            preload: 'Specifies if and how the author thinks the video should be loaded when the page loads',
            src: 'Specifies the URL of the video file',
            width: 'Sets the width of the video player',
        },
        category: 'media',
    },
    wbr: {
        description: 'The <wbr> (Word Break Opportunity) tag specifies where in a text it would be ok to add a line-break.',
        category: 'format',
    }
};