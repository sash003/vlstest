

// Создает обработчик события window.onLoad
        //YMaps.jQuery(function () {
            // Создает экземпляр карты и привязывает его к созданному контейнеру
            var map = new YMaps.Map(YMaps.jQuery("#YMapsID")[0]);
            
            // Устанавливает начальные параметры отображения карты: центр карты и коэффициент масштабирования
            map.setCenter(new YMaps.GeoPoint(37.64, 55.76), 11);
            
            map.addControl(new YMaps.TypeControl());
            map.addControl(new YMaps.ToolBar());
            map.addControl(new YMaps.Zoom());
            map.addControl(new YMaps.ScaleLine());
                     
        //});
        
        var d = document,
        showMap = d.querySelector('.app_show'),
        first = d.getElementById('first'),
        second = d.getElementById('second'),
        city = d.getElementById('city'),
        show = d.getElementById('show'),
        routeShow = d.getElementById('route');
    
    addEvent(showMap, 'click', function(){
        d.getElementById('YMapsID').show();
    });
    
    addEvent(show, 'click', function(e){
        preventdefault(e);
        
        var point1 = city.val() + ', ' + first.val(),
            point2 = city.val() + ', ' + second.val();
            //console.log(point1, point2);
            
            
        var map = new YMaps.Map(YMaps.jQuery("#YMapsID")[0]);
            
            // Устанавливает начальные параметры отображения карты: центр карты и коэффициент масштабирования
            map.setCenter(new YMaps.GeoPoint(37.64, 55.76), 10);
            
            map.addControl(new YMaps.TypeControl());
            map.addControl(new YMaps.ToolBar());
            map.addControl(new YMaps.Zoom());
            map.addControl(new YMaps.ScaleLine());
            
        var router = new YMaps.Router(
       // Список точек, которые необходимо посетить
       [point1,point2],[],
       { viewAutoApply: true }
   );
map.addOverlay(router); // Добавляет на карту полный маршрут

YMaps.Events.observe(router, router.Events.Success, function () {
   var route = router.getRoute(0);
   var itineraryList = ['Трогаемся.'];
   var action = [];
   action['back'] = 'назад';
   action['left'] = 'налево';
   action['right'] = 'направо';
   action['none'] = 'прямо';
   
   for (var i=0; i < route.getNumRouteSegments(); i++) {
      var segment = route.getRouteSegment(i);
      
      if(action[segment.getAction()]){
          way = action[segment.getAction()];
      }
      
      if(segment.getStreet()){
          Street = ' на ' + segment.getStreet();
      }
      itineraryList.push('Едем ' + way + Street + ', проезжаем ' + segment.getDistance() + ' м.');
   }
   
   itineraryList.push('Останавливаемся.');
   routeShow.html(itineraryList.join('<br>'));
});
   
        
    });
    
/* кроссбраузерная функция отмены действия по умолчанию */
function preventdefault(e){
    e = e || window.event;
    if(e.preventDefault) e.preventDefault();
    else e.returnValue  = false;
    
}

// функция кроссбраузерной установки обработчиков событий
function addEvent(elem, type, handler, param){
    param = param || false;
  if(elem.addEventListener){
    elem.addEventListener(type, handler, param);
  } else {
    elem.attachEvent('on'+type, handler);
  }
  return false;
}

Element.prototype.val = function(val){
    if(val && typeof val === 'string'){
         this.value = val;   
        return this;
    }
    else return this.value;
}

Element.prototype.html = function(html){
    if(html && typeof html === 'string'){
        this.innerHTML = html;   
      return this;
    }
    else return this.innerHTML;
}

Element.prototype.show = function(){
    this.css({'display':'block'});
    return this;
}

Element.prototype.css = function(obj){
    if(typeof obj === 'object'){
         for (i in obj){
         this.style[i] = obj[i];   
     }
    }
    else {
        if(obj === 'width') return this.offsetWidth;
        if(obj === 'height') return this.offsetHeight
        else return this.style[obj] || window.getComputedStyle(this)[obj] || this.currentStyle[obj];
    }    
    return this;
}
