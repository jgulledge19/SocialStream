/* create a local combo box */
Socialstream.combo.SocialService = function(config) {
    config = config || {};
    Ext.applyIf(config,{
       //displayField: 'name'
        //,valueField: 'id'
        //,fields: ['id', 'name']
        store: ['Twitter','Facebook','FacebookSearch', 'RSS', 'YouTube']
        //,url: Testapp.config.connectorUrl
        ,baseParams: { action: '' ,combo: true }
        ,mode: 'local'
        ,editable: false
    });
    Socialstream.combo.SocialService.superclass.constructor.call(this,config);
};
//Ext.extend(MODx.combo.FeedStatus, MODx.combo.ComboBox);
Ext.extend(Socialstream.combo.SocialService,MODx.combo.ComboBox);
Ext.reg('socialservice-combo', Socialstream.combo.SocialService);


/* YOU will need to edit this file with proper names, follow the cases(upper/lower) */
Socialstream.grid.Accounts = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'socialstream-grid-accounts'
        ,url: Socialstream.config.connectorUrl
        ,baseParams: { action: 'mgr/account/getList' }
        ,save_action: 'mgr/account/updateFromGrid'// wrong path
        ,fields: ['id','service','username','active','auto_approve','get_feeds','name','description','public_url', 'feed_url']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'feed'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 30
        },{
            header: _('socialstream.service')
            ,dataIndex: 'service'
            ,sortable: true
            ,width: 45 
            ,editor: { xtype: 'socialservice-combo', renderer: 'value' }// 'textfield' } 
        },{
            header: _('socialstream.username')
            ,dataIndex: 'username'
            ,sortable: true
            ,width: 65 
            ,editor: { xtype: 'textfield' } 
        },{
            header: _('socialstream.active')
            ,dataIndex: 'active'
            ,sortable: true
            ,width: 50 ,
            editor: { xtype: 'combo-boolean' ,renderer: 'boolean' /*'boolean'*/ }
        },{
            header: _('socialstream.auto_approve')
            ,dataIndex: 'auto_approve'
            ,sortable: true
            ,width: 50 ,
            editor: { xtype: 'combo-boolean' ,renderer: 'boolean' /*'boolean'*/ }
        },{
            header: _('socialstream.get_feeds')
            ,dataIndex: 'get_feeds'
            ,sortable: true
            ,width: 50 ,
            editor: { xtype: 'combo-boolean' ,renderer: 'boolean' /*'boolean'*/ }
        },{
            header: _('socialstream.name')
            ,dataIndex: 'name'
            ,sortable: true
            ,width: 60 
            ,editor: { xtype: 'textfield' }
        },{
            header: _('socialstream.account_description')
            ,dataIndex: 'description'
            ,sortable: false
            ,width: 150 
            ,editor: { xtype: 'textfield' } 
        }]
        ,tbar: [{
            xtype: 'textfield'
            ,id: 'account-search-filter'
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
            text: _('socialstream.account_create')
            ,handler: { xtype: 'socialstream-window-account-create' ,blankValues: true }
        }]
    });
    Socialstream.grid.Accounts.superclass.constructor.call(this,config);
};

Ext.extend(Socialstream.grid.Accounts,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() {
        var m = [{
            text: _('socialstream.account_update')
            ,handler: this.updateAccount
        },'-',{
            text: _('socialstream.account_remove')
            ,handler: this.removeAccount
        }];
        this.addContextMenuItem(m);
        
        return true;
    }
    ,updateAccount: function(btn,e) {
        if (!this.updateAccountWindow) {
            this.updateAccountWindow = MODx.load({
                xtype: 'socialstream-window-account-update'// socialstream-window-account-update
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.updateAccountWindow.setValues(this.menu.record);
        }
        this.updateAccountWindow.show(e.target);
    }

    ,removeAccount: function() {
        MODx.msg.confirm({
            title: _('socialstream.account_remove')
            ,text: _('socialstream.account_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/account/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('socialstream-grid-accounts',Socialstream.grid.Accounts);


Socialstream.window.CreateAccount = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('socialstream.account_create')
        ,url: Socialstream.config.connectorUrl
        ,baseParams: {
            action: 'mgr/account/create'
        }
        ,width: 600
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('socialstream.username')
            ,name: 'username'
            ,width: 300
        },{
            fieldLabel: _('socialstream.service')
            ,name: 'service'
            ,width: 300 
            //,xtype: 'textfield' 
            ,xtype: 'socialservice-combo', renderer: 'value'
        },{
            fieldLabel: _('socialstream.active')
            ,name: 'active'
            ,width: 100
            ,value: 1
            ,xtype: 'combo-boolean'
            ,renderer: 'boolean'
        },{
            fieldLabel: _('socialstream.auto_approve')
            ,name: 'auto_approve'
            ,width: 100
            ,xtype: 'combo-boolean' 
            ,renderer: 'boolean'
        },{
            fieldLabel: _('socialstream.get_feeds_desc')
            ,name: 'get_feeds'
            ,width: 100
            ,value: 1
            ,xtype: 'combo-boolean'
            ,renderer: 'boolean'
        },{
            fieldLabel: _('socialstream.name')
            ,name: 'name'
            ,width: 300 
            ,xtype: 'textfield'
        },{
            fieldLabel: _('socialstream.account_description')
            ,name: 'description'
            ,width: 300
            ,xtype: 'textarea' 
        },{
            fieldLabel: _('socialstream.public_url')
            ,name: 'public_url'
            ,width: 300 
            ,xtype: 'textfield'
        },{
            fieldLabel: _('socialstream.feed_url')
            ,name: 'feed_url'
            ,width: 300 
            ,xtype: 'textfield'
        },]
    });
    Socialstream.window.CreateAccount.superclass.constructor.call(this,config);
};
Ext.extend(Socialstream.window.CreateAccount,MODx.Window);
Ext.reg('socialstream-window-account-create',Socialstream.window.CreateAccount);


Socialstream.window.UpdateAccount = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('socialstream.account_update')
        ,url: Socialstream.config.connectorUrl
        ,baseParams: {
            action: 'mgr/account/update'
        }
        ,width: 600
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        }, {
            xtype: 'textfield'
            ,fieldLabel: _('socialstream.username')
            ,name: 'username'
            ,width: 300
        },/*{
            //xtype: 'textfield'
            xtype: 'feedstatus-combo'
            ,renderer: true
            ,fieldLabel: _('socialstream.status')
            ,name: 'status'
            ,width: 300
        },*/{
            fieldLabel: _('socialstream.service')
            ,name: 'service'
            ,width: 300 
            //,xtype: 'textfield' 
            ,xtype: 'socialservice-combo', renderer: 'value'
        },{
            fieldLabel: _('socialstream.active')
            ,name: 'active'
            ,width: 100
            ,'default': 1
            ,xtype: 'combo-boolean'
            ,renderer: 'boolean'
        },{
            fieldLabel: _('socialstream.auto_approve')
            ,name: 'auto_approve'
            ,width: 100
            ,xtype: 'combo-boolean' 
            ,renderer: 'boolean'
        },{
            fieldLabel: _('socialstream.get_feeds_desc')
            ,name: 'get_feeds'
            ,width: 100
            ,'default': 1
            ,xtype: 'combo-boolean'
            ,renderer: 'boolean'
        },{
            fieldLabel: _('socialstream.name')
            ,name: 'name'
            ,width: 300 
            ,xtype: 'textfield'
        },{
            fieldLabel: _('socialstream.account_description')
            ,name: 'description'
            ,width: 300
            ,xtype: 'textarea' 
        },{
            fieldLabel: _('socialstream.public_url')
            ,name: 'public_url'
            ,width: 300 
            ,xtype: 'textfield'
        },{
            fieldLabel: _('socialstream.feed_url')
            ,name: 'feed_url'
            ,width: 300 
            ,xtype: 'textfield'
        },]
    });
    Socialstream.window.UpdateAccount.superclass.constructor.call(this,config);
};
Ext.extend(Socialstream.window.UpdateAccount,MODx.Window);
Ext.reg('socialstream-window-account-update',Socialstream.window.UpdateAccount);