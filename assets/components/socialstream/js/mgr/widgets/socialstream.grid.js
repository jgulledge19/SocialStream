/* create a local combo box */
/*var comboData=['approved','hidden','pending','auto_approved'];  
  
var myLocalCombo =new Ext.form.ComboBox({
    //fieldLabel:'Frameworks PHP',  
    name:'status',
    forceSelection:true,  
    store:data,
    emptyText:'Select',  
    triggerAction: 'all',  
    //hideTrigger:true,  
    editable:false,  
    //minChars:3  
});*/
Socialstream.combo.FeedStatus = function(config) {
    config = config || {};
    Ext.applyIf(config,{
       //displayField: 'name'
        //,valueField: 'id'
        //,fields: ['id', 'name']
        store: ['approved','hidden','pending','auto_approved']
        //,url: Testapp.config.connectorUrl
        ,baseParams: { action: '' ,combo: true }
        //,mode: 'local'
        ,editable: false
    });
    Socialstream.combo.FeedStatus.superclass.constructor.call(this,config);
};
//Ext.extend(MODx.combo.FeedStatus, MODx.combo.ComboBox);
Ext.extend(Socialstream.combo.FeedStatus,MODx.combo.ComboBox);
Ext.reg('feedstatus-combo', Socialstream.combo.FeedStatus);


/* YOU will need to edit this file with proper names, follow the cases(upper/lower) */
Socialstream.grid.Socialstream = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'socialstream-grid-socialstream'
        ,url: Socialstream.config.connectorUrl
        ,baseParams: { action: 'mgr/socialstream/getList' }
        ,save_action: 'mgr/socialstream/updateFromGrid'// wrong path
        ,fields: ['id','service','username','post_date','status','feed']
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
            ,width: 45 /* NOT Editiable
            ,editor: { xtype: 'textfield' } */
        },{
            header: _('socialstream.username')
            ,dataIndex: 'username'
            ,sortable: true
            ,width: 65 /* NOT Editiable
            ,editor: { xtype: 'textfield' } */
        },{
            header: _('socialstream.post_date')
            ,dataIndex: 'post_date'
            ,sortable: true
            ,width: 60/*
            //,name: 'post_date'
            ,hiddenName: 'post_date'
            ,anchor: '90%'
            ,dateFormat: MODx.config.manager_date_format
            ,timeFormat: MODx.config.manager_time_format
            ,dateWidth: 120
            ,timeWidth: 120*/
        },{
            header: _('socialstream.status')
            ,dataIndex: 'status'
            ,sortable: true
            ,width: 60 
            ,editor: //{ xtype: 'textfield' } 
            { xtype: 'feedstatus-combo', renderer: 'value' }
            //{ xtype: 'combo-boolean', renderer: true, mode: 'local', feilds:[ 'approved', 'pending', 'auto_approved',] }
            // ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
        },{
            header: _('socialstream.feed')
            ,dataIndex: 'feed'
            ,sortable: false
            ,width: 200
            ,wrap: true /* NOT Editiable?
            ,editor: { xtype: 'textfield' } */
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
            text: _('socialstream.get_lastest')
            ,handler: { xtype: 'socialstream-window-get-latest' ,blankValues: true }
        }]
    });
    Socialstream.grid.Socialstream.superclass.constructor.call(this,config);
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
            text: _('socialstream.feed_update')
            ,handler: this.updateFeed
        },'-',{
            text: _('socialstream.feed_remove')
            ,handler: this.removeFeed
        }];
        this.addContextMenuItem(m);
        
        return true;
    }
    ,updateFeed: function(btn,e) {
        console.log('Update');
        if (!this.updateFeedWindow) {
            this.updateFeedWindow = MODx.load({
                xtype: 'socialstream-window-socialstream-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.updateFeedWindow.setValues(this.menu.record);
        }
        this.updateFeedWindow.show(e.target);
    }

    ,removeFeed: function() {
        MODx.msg.confirm({
            title: _('socialstream.feed_remove')
            ,text: _('socialstream.feed_remove_confirm')
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


Socialstream.window.UpdateFeed = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('socialstream.feed_update')
        ,url: Socialstream.config.connectorUrl
        ,baseParams: {
            action: 'mgr/socialstream/update'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('socialstream.username')
            ,name: 'username'
            ,width: 300
            ,disable: true
            ,editable: false
        },{
            //xtype: 'textfield'
            xtype: 'feedstatus-combo'
            ,renderer: true
            ,fieldLabel: _('socialstream.status')
            ,name: 'status'
            ,width: 150
        },{
            xtype: 'textarea'
            ,fieldLabel: _('socialstream.feed')
            ,name: 'feed'
            ,width: 300
            ,readonly: true
            ,disable: true
            ,editable: false
        }]
    });
    Socialstream.window.UpdateFeed.superclass.constructor.call(this,config);
};
Ext.extend(Socialstream.window.UpdateFeed,MODx.Window);
Ext.reg('socialstream-window-socialstream-update',Socialstream.window.UpdateFeed);

Socialstream.window.GetLatestPosts = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('socialstream.feed_get_latest')
        ,url: Socialstream.config.connectorUrl
        ,baseParams: {
            action: 'mgr/socialstream/getlatest'
        }
        ,fields: [
        { 
            html: _('socialstream.feed_get_latest_desc')+'<br />'
        }/*,{
            xtype: 'textfield'
            ,fieldLabel: 
            ,name: 'username'
            ,width: 0
        }*//* maybe have a list of user accounts to choose for and/or services? */
        ]
    });
    Socialstream.window.GetLatestPosts.superclass.constructor.call(this,config);
};
Ext.extend(Socialstream.window.GetLatestPosts,MODx.Window);
Ext.reg('socialstream-window-get-latest',Socialstream.window.GetLatestPosts);
