/* YOU will need to edit this file with proper names, follow the cases(upper/lower) */
Socialstream.grid.Socialstream = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'socialstream-grid-socialstream'
        ,url: Socialstream.config.connectorUrl
        ,baseParams: { action: 'mgr/socialstream/getList' }
        ,save_action: 'mgr/socialstream/updateFromGrid'
        ,fields: ['id','name','description']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'name'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 60
        },{
            header: _('socialstream.name')
            ,dataIndex: 'name'
            ,sortable: true
            ,width: 100
            ,editor: { xtype: 'textfield' }
        },{
            header: _('socialstream.description')
            ,dataIndex: 'description'
            ,sortable: false
            ,width: 350
            ,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
            xtype: 'textfield'
            ,id: 'socialstream-search-filter'
            ,emptyText: _('socialstream.search...')
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        }
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            text: _('socialstream.socialstream_create')
            ,handler: { xtype: 'socialstream-window-socialstream-create' ,blankValues: true }
        }]
    });
    // BROKE
    Socialstream.grid.Socialstream.superclass.constructor.call(this,config);
    console.log('Start Social Grid');
};
Ext.extend(Socialstream.grid.Socialstream,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() {
        var m = [{
            text: _('socialstream.socialstream_update')
            ,handler: this.updateDoodle
        },'-',{
            text: _('socialstream.socialstream_remove')
            ,handler: this.removeDoodle
        }];
        this.addContextMenuItem(m);
        
    console.log('Start Social Menu');
        return true;
    }
    ,updateDoodle: function(btn,e) {
        if (!this.updateDoodleWindow) {
            this.updateDoodleWindow = MODx.load({
                xtype: 'socialstream-window-socialstream-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.updateDoodleWindow.setValues(this.menu.record);
        }
        this.updateDoodleWindow.show(e.target);
    }

    ,removeDoodle: function() {
        MODx.msg.confirm({
            title: _('socialstream.socialstream_remove')
            ,text: _('socialstream.socialstream_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/socialstream/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('socialstream-grid-socialstream',Socialstream.grid.Socialstream);


Socialstream.window.CreateDoodle = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('socialstream.socialstream_create')
        ,url: Socialstream.config.connectorUrl
        ,baseParams: {
            action: 'mgr/socialstream/create'
        }
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('socialstream.name')
            ,name: 'name'
            ,width: 300
        },{
            xtype: 'textarea'
            ,fieldLabel: _('socialstream.description')
            ,name: 'description'
            ,width: 300
        }]
    });
    Socialstream.window.CreateDoodle.superclass.constructor.call(this,config);
};
Ext.extend(Socialstream.window.CreateDoodle,MODx.Window);
Ext.reg('socialstream-window-socialstream-create',Socialstream.window.CreateDoodle);


Socialstream.window.UpdateDoodle = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('socialstream.socialstream_update')
        ,url: Socialstream.config.connectorUrl
        ,baseParams: {
            action: 'mgr/socialstream/update'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('socialstream.name')
            ,name: 'name'
            ,width: 300
        },{
            xtype: 'textarea'
            ,fieldLabel: _('socialstream.description')
            ,name: 'description'
            ,width: 300
        }]
    });
    Socialstream.window.UpdateDoodle.superclass.constructor.call(this,config);
};
Ext.extend(Socialstream.window.UpdateDoodle,MODx.Window);
Ext.reg('socialstream-window-socialstream-update',Socialstream.window.UpdateDoodle);