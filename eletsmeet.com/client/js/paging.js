function Pager(tableName, itemsPerPage, pType) {
    this.tableName = tableName;
    this.itemsPerPage = itemsPerPage;
    this.currentPage = 1;
    this.pages = 0;
    this.inited = false;

    this.showRecords = function(from, to) {
        var rows = document.getElementById(tableName).rows;
        // i starts from 1 to skip table header row
        for (var i = 1; i < rows.length; i++) {
            if (i < from || i > to)
                rows[i].style.display = 'none';
            else
                rows[i].style.display = '';
        }
    }

    this.showPage = function(pageNumber) {
    	if (! this.inited) {
    		alert("not inited");
    		return;
    	}
      if(this.pages > 1) {
        var oldPageAnchor = document.getElementById(pType+'pg'+this.currentPage);
        oldPageAnchor.className = 'deactive';

        this.currentPage = pageNumber;
        var newPageAnchor = document.getElementById(pType+'pg'+this.currentPage);
        newPageAnchor.className = 'active';
        var prev = document.getElementById(pType+'prev');
        var next = document.getElementById(pType+'next');
        if(this.currentPage > 1)
        {
            prev.style.display = 'inline';
        } else {
            prev.style.display = 'none';
        }
        var from = (pageNumber - 1) * itemsPerPage + 1;
        var to = from + itemsPerPage - 1;
        this.showRecords(from, to);
        if(this.currentPage == this.pages)
        {
            next.style.display = 'none';
        } else {
            next.style.display = 'inline';
        }
    }
    }

    this.prev = function() {
        if (this.currentPage > 1)
            this.showPage(this.currentPage - 1);
    }

    this.next = function() {
        if (this.currentPage < this.pages) {
            this.showPage(this.currentPage + 1);
        }
    }

    this.init = function() {
        var rows = document.getElementById(tableName).rows;
        var records = (rows.length - 1);
        this.pages = Math.ceil(records / itemsPerPage);
        this.inited = true;
    }

    this.showPageNav = function(pagerName, positionId) {
    	if (! this.inited) {
    		alert("not inited");
    		return;
    	}
    	var element = document.getElementById(positionId);

    	var pagerHtml = '<ul><li id="'+pType+'prev" style="display:none;" onclick="' + pagerName + '.prev();"><a> &#171  </a></li>';
        for (var page = 1; page <= this.pages; page++)
            pagerHtml += '<li id="'+pType+'pg' + page + '" onclick="' + pagerName + '.showPage(' + page + ');"><a>'+page+'</a></li>';
        pagerHtml += '<li id="'+pType+'next" style="display:inline;" onclick="'+pagerName+'.next();"><a>  &#187; </a></li></ul>';
        if(this.pages > 1)
        element.innerHTML = pagerHtml;
    }
}
/*function Pager(tableName, itemsPerPage) {
    this.tableName = tableName;
    this.itemsPerPage = itemsPerPage;
    this.currentPage = 1;
    this.pages = 0;
    this.inited = false;
    
    this.showRecords = function(from, to) {        
        var rows = document.getElementById(tableName).rows;
        // i starts from 1 to skip table header row
        for (var i = 1; i < rows.length; i++) {
            if (i < from || i > to)  
                rows[i].style.display = 'none';
            else
                rows[i].style.display = '';
        }
    }
    
    this.showPage = function(pageNumber) {
    	if (! this.inited) {
    		alert("not inited");
    		return;
    	}
      if(this.pages > 1) {
        var oldPageAnchor = document.getElementById('pg'+this.currentPage);
        oldPageAnchor.className = 'deactive';
        
        this.currentPage = pageNumber;
        var newPageAnchor = document.getElementById('pg'+this.currentPage);
        newPageAnchor.className = 'active';
        var prev = document.getElementById('prev');
        var next = document.getElementById('next');
        if(this.currentPage > 1)
        {
            prev.style.display = 'inline';
        } else {
            prev.style.display = 'none';
        }
        var from = (pageNumber - 1) * itemsPerPage + 1;
        var to = from + itemsPerPage - 1;
        this.showRecords(from, to);
        if(this.currentPage == this.pages)
        {
            next.style.display = 'none';
        } else {
            next.style.display = 'inline';
        }
    }
    }   
    
    this.prev = function() {
        if (this.currentPage > 1)
            this.showPage(this.currentPage - 1);
    }
    
    this.next = function() {
        if (this.currentPage < this.pages) {
            this.showPage(this.currentPage + 1);
        }
    }                        
    
    this.init = function() {
        var rows = document.getElementById(tableName).rows;
        var records = (rows.length - 1); 
        this.pages = Math.ceil(records / itemsPerPage);
        this.inited = true;
    }

    this.showPageNav = function(pagerName, positionId) {
    	if (! this.inited) {
    		alert("not inited");
    		return;
    	}
    	var element = document.getElementById(positionId);
    	
    	var pagerHtml = '<ul><li id="prev" style="display:none;" onclick="' + pagerName + '.prev();"><a> &#171  </a></li>';
        for (var page = 1; page <= this.pages; page++) 
            pagerHtml += '<li id="pg' + page + '" onclick="' + pagerName + '.showPage(' + page + ');"><a>'+page+'</a></li>';
        pagerHtml += '<li id="next" style="display:inline;" onclick="'+pagerName+'.next();"><a>  &#187; </a></li></ul>';            
        if(this.pages > 1)
        element.innerHTML = pagerHtml;
    }
}

*/