var Socailstream = function(config) {
    config = config || {};
    //console.log('Start Social');
    Socailstream.superclass.constructor.call(this,config);
};
Ext.extend(Socailstream,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
//console.log('Extend Social');
Ext.reg('socailstream',Socailstream);

//console.log('Start Social Class');
Socailstream = new Socailstream();