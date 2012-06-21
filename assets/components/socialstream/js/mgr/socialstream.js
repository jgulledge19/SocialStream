var Socialstream = function(config) {
    config = config || {};
    //console.log('Start Social');
    Socialstream.superclass.constructor.call(this,config);
};
Ext.extend(Socialstream,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
//console.log('Extend Social');
Ext.reg('socialstream',Socialstream);

//console.log('Start Social Class');
Socialstream = new Socialstream();