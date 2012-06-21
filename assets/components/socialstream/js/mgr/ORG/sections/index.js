/* YOU will need to edit this file with proper names, follow the cases(upper/lower) */
Ext.onReady(function() {
    MODx.load({ xtype: 'socialstream-page-home'});
});

Socialstream.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'socialstream-panel-home'
            ,renderTo: 'socialstream-panel-home-div'
        }]
    });
    Socialstream.page.Home.superclass.constructor.call(this,config);
};

console.log('Start Social Section');
Ext.extend(Socialstream.page.Home,MODx.Component);
Ext.reg('socialstream-page-home',Socialstream.page.Home);