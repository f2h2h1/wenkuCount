window.pageCount=function(){
    this.url = "http://localhost/thinkphp/index.php/IdCount/index/";
    this.view=function(id, callback){
        if (id.length>256){
            return null
        }
        var url;
        url = "view";
        url = this.url+"/"+url+"?id="+id;
        return ajax(url, callback);
    }
    this.read=function(id, callback){
        if (id.length>256){
            return null
        }
        var url;
        url = "read";
        url = this.url+"/"+url+"?id="+id;
        return ajax(url, callback);
    }
    function ajax(url, callback){
        $.ajax({
            async: true,
            type: "GET",
            url: url,
        }).done(function( msg ) {
            var result = JSON.parse(msg);
            callback(result.count);
            return result.count;
        });
    }
}
window.wenkuCounter = new pageCount();