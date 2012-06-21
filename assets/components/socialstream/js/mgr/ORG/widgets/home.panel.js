/* YOU will need to edit this file with proper names, follow the cases(upper/lower) */
Socialstream.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('socialstream.management')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,bodyStyle: 'padding: 10px'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,items: [{
                title: _('socialstream')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('socialstream.management_desc')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'socialstream-grid-socialstream'
                    ,preventRender: true
                }]
            }]
        }]
    });
    Socialstream.panel.Home.superclass.constructor.call(this,config);
};

console.log('Start Social Panel');
Ext.extend(Socialstream.panel.Home,MODx.Panel);
Ext.reg('socialstream-panel-home',Socialstream.panel.Home);
