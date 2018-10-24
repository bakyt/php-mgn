@section('after_jquery')
    <link rel="stylesheet" href="/css/slider.css">
    <script src="/js/jssor.slider-27.1.0.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            var jssor_1_SlideshowTransitions = [
              {$Duration:500,$Delay:40,$Cols:10,$Rows:5,$Opacity:2,$Clip:15,$SlideOut:true,$Easing:$Jease$.$OutQuad},
              {$Duration:800,y:0.3,$Cols:2,$During:{$Top:[0.3,0.7]},$ChessMode:{$Column:12},$Easing:{$Top:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2},
              {$Duration:1000,x:-1,y:2,$Rows:2,$Zoom:11,$Rotate:1,$SlideOut:true,$Assembly:2049,$ChessMode:{$Row:15},$Easing:{$Left:$Jease$.$InExpo,$Top:$Jease$.$InExpo,$Zoom:$Jease$.$InExpo,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InExpo},$Opacity:2,$Round:{$Rotate:0.85}},
              {$Duration:1200,x:4,$Cols:2,$Zoom:11,$SlideOut:true,$Assembly:2049,$ChessMode:{$Column:15},$Easing:{$Left:$Jease$.$InExpo,$Zoom:$Jease$.$InExpo,$Opacity:$Jease$.$Linear},$Opacity:2},
              {$Duration:1000,x:4,y:-4,$Zoom:11,$Rotate:1,$SlideOut:true,$Easing:{$Left:$Jease$.$InQuint,$Top:$Jease$.$InQuint,$Zoom:$Jease$.$InQuart,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuint},$Opacity:2,$Round:{$Rotate:0.8}},
              {$Duration:1500,x:0.3,y:-0.3,$Delay:80,$Cols:10,$Rows:5,$Opacity:2,$Clip:15,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$Easing:{$Left:$Jease$.$InJump,$Top:$Jease$.$InJump,$Clip:$Jease$.$OutQuad},$Round:{$Left:0.8,$Top:2.5}},
              {$Duration:1000,x:-3,y:1,$Rows:2,$Zoom:11,$Rotate:1,$SlideOut:true,$Assembly:2049,$ChessMode:{$Row:28},$Easing:{$Left:$Jease$.$InExpo,$Top:$Jease$.$InExpo,$Zoom:$Jease$.$InExpo,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InExpo},$Opacity:2,$Round:{$Rotate:0.7}},
              {$Duration:1000,x:0.5,y:0.5,$Zoom:1,$Rotate:1,$SlideOut:true,$Easing:{$Left:$Jease$.$InCubic,$Top:$Jease$.$InCubic,$Zoom:$Jease$.$InCubic,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InCubic},$Opacity:2,$Round:{$Rotate:0.5}},
              {$Duration:1200,x:-0.6,y:-0.6,$Zoom:1,$Rotate:1,$During:{$Left:[0.2,0.8],$Top:[0.2,0.8],$Zoom:[0.2,0.8],$Rotate:[0.2,0.8]},$Opacity:2,$Round:{$Rotate:0.5}},
              {$Duration:1200,y:-0.5,$Delay:30,$Cols:15,$Opacity:2,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationCircle,$Easing:{$Top:$Jease$.$InWave,$Opacity:$Jease$.$Linear},$Round:{$Top:1.5}},
              {$Duration:500,$Delay:12,$Cols:10,$Rows:5,$Opacity:2,$Clip:15,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Assembly:2050,$Easing:{$Clip:$Jease$.$InSine}},
              {$Duration:800,$Delay:20,$Clip:3,$SlideOut:true,$Assembly:260,$Easing:{$Clip:$Jease$.$OutCubic,$Opacity:$Jease$.$Linear},$Opacity:2}
            ];

            var jssor_1_SlideoTransitions = [
              [{b:-1,d:1,o:-1},{b:0,d:1200,y:300,o:1,e:{y:24,o:6}},{b:5600,d:800,y:-200,o:-1,e:{y:5}}],
              [{b:-1,d:1,o:-1},{b:400,d:800,x:200,o:1,e:{x:27,o:6}},{b:5600,d:800,x:-200,o:-1,e:{x:5}}],
              [{b:-1,d:1,o:-1},{b:400,d:800,x:-200,o:1,e:{x:27,o:6}},{b:5600,d:800,x:200,o:-1,e:{x:5}}],
              [{b:-1,d:1,o:-1},{b:1600,d:600,x:200,y:-230,o:1,e:{x:3,y:3}},{b:5600,d:800,o:-1}],
              [{b:4600,d:960,x:-204,e:{x:1}}],
              [{b:-1,d:1,sX:-1,sY:-1},{b:3400,d:400,sX:1,sY:1},{b:3800,d:300,o:-1,sX:0.1,sY:0.1}],
              [{b:-1,d:1,sX:-1,sY:-1},{b:3520,d:400,sX:1,sY:1},{b:3920,d:300,o:-1,sX:0.1,sY:0.1}],
              [{b:-1,d:1,o:-1},{b:2200,d:1200,x:-135,y:-24,o:1,e:{x:7,y:7}},{b:4600,d:640,x:-130,e:{x:1}}],
              [{b:-1,d:1,o:-1},{b:4600,d:240,x:-75,o:1},{b:4840,d:480,x:-150},{b:5320,d:240,x:-75,o:-1}],
              [{b:2800,d:600,y:70,sX:-0.5,sY:-0.5,e:{y:5}},{b:6000,d:600,y:50,r:-10},{b:7000,d:400,o:-1,rX:10,rY:-10}],
              [{b:0,d:600,x:-742,sX:4,sY:4,e:{x:6}},{b:900,d:600,sX:-4,sY:-4}],
              [{b:-1,d:1,o:-1},{b:400,d:500,o:1,e:{o:5}}],
              [{b:-1,d:1,o:-1,r:-180},{b:1500,d:500,o:1,r:180,e:{r:27}}],
              [{b:-1,d:1,o:-1,r:180},{b:2000,d:500,o:1,r:-180,e:{r:27}}],
              [{b:2800,d:600,y:-270,e:{y:6}}],
              [{b:6000,d:600,y:-100,r:-10,e:{y:6}},{b:7000,d:400,o:-1,rX:-10,rY:10}],
              [{b:-1,d:1,sX:-1,sY:-1},{b:3400,d:400,sX:1.33,sY:1.33,e:{sX:7,sY:7}},{b:3800,d:200,sX:-0.33,sY:-0.33,e:{sX:16,sY:16}}],
              [{b:-1,d:1,o:-1},{b:3400,d:600,o:1},{b:4000,d:1000,r:360,e:{r:1}}],
              [{b:-1,d:1,o:-1},{b:3400,d:600,y:-70,o:1,e:{y:27}}],
              [{b:-1,d:1,sX:-1,sY:-1},{b:3700,d:400,sX:1.33,sY:1.33,e:{sX:7,sY:7}},{b:4100,d:200,sX:-0.33,sY:-0.33,e:{sX:16,sY:16}}],
              [{b:-1,d:1,o:-1},{b:3700,d:600,o:1},{b:4300,d:1000,r:360}],
              [{b:-1,d:1,o:-1},{b:3700,d:600,x:-150,o:1,e:{x:27}}],
              [{b:-1,d:1,sX:-1,sY:-1},{b:4000,d:400,sX:1.33,sY:1.33,e:{sX:7,sY:7}},{b:4400,d:200,sX:-0.33,sY:-0.33,e:{sX:16,sY:16}}],
              [{b:-1,d:1,o:-1},{b:4000,d:600,o:1},{b:4600,d:1000,r:360}],
              [{b:-1,d:1,o:-1},{b:4000,d:600,x:150,o:1,e:{x:27}}],
              [{b:9300,d:600,o:-1,r:540,sX:-0.5,sY:-0.5,e:{r:5}}],
              [{b:-1,d:1,o:-1,sX:2,sY:2},{b:6880,d:20,o:1},{b:6900,d:300,sX:-2.08,sY:-2.08,e:{sX:27,sY:27}},{b:7200,d:240,sX:0.08,sY:0.08}],
              [{b:-1,d:1,o:-1,sX:5,sY:5},{b:6300,d:600,o:1,sX:-5,sY:-5}],
              [{b:-1,d:1,o:-1},{b:7200,d:440,o:1}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:7420,d:20,o:1},{b:7440,d:200,r:180,sX:0.4,sY:0.4},{b:7640,d:200,r:180,sX:0.5,sY:0.5}],
              [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:7620,d:20,o:1},{b:7640,d:300,r:60,sX:1.1,sY:1.1},{b:7940,d:160,sX:-0.2,sY:-0.2}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:7920,d:20,o:1},{b:7940,d:300,sX:1.4,sY:1.4},{b:8240,d:160,sX:-0.5,sY:-0.5}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:7620,d:20,o:1},{b:7640,d:200,r:180,sX:0.4,sY:0.4},{b:7840,d:200,r:180,sX:0.5,sY:0.5}],
              [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:7820,d:20,o:1},{b:7840,d:300,r:60,sX:1.1,sY:1.1},{b:8140,d:160,sX:-0.2,sY:-0.2}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8120,d:20,o:1},{b:8140,d:300,sX:1.4,sY:1.4},{b:8440,d:160,sX:-0.5,sY:-0.5}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:7820,d:20,o:1},{b:7840,d:200,r:180,sX:0.4,sY:0.4},{b:8040,d:200,r:180,sX:0.5,sY:0.5}],
              [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:8020,d:20,o:1},{b:8040,d:300,r:60,sX:1.1,sY:1.1},{b:8340,d:160,sX:-0.2,sY:-0.2}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8320,d:20,o:1},{b:8340,d:300,sX:1.4,sY:1.4},{b:8640,d:160,sX:-0.5,sY:-0.5}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8020,d:20,o:1},{b:8040,d:200,r:180,sX:0.4,sY:0.4},{b:8240,d:200,r:180,sX:0.5,sY:0.5}],
              [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:8220,d:20,o:1},{b:8240,d:300,r:60,sX:1.1,sY:1.1},{b:8540,d:160,sX:-0.2,sY:-0.2}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8520,d:20,o:1},{b:8540,d:300,sX:1.4,sY:1.4},{b:8840,d:160,sX:-0.5,sY:-0.5}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8220,d:20,o:1},{b:8240,d:200,r:180,sX:0.4,sY:0.4},{b:8440,d:200,r:180,sX:0.5,sY:0.5}],
              [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:8420,d:20,o:1},{b:8440,d:300,r:60,sX:1.1,sY:1.1},{b:8740,d:160,sX:-0.2,sY:-0.2}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8720,d:20,o:1},{b:8740,d:300,sX:1.4,sY:1.4},{b:9040,d:160,sX:-0.5,sY:-0.5}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8220,d:20,o:1},{b:8240,d:200,r:180,sX:0.4,sY:0.4},{b:8440,d:200,r:180,sX:0.5,sY:0.5}],
              [{b:-1,d:1,o:-1,r:-60,sX:-0.9,sY:-0.9},{b:8420,d:20,o:1},{b:8440,d:300,r:60,sX:1.1,sY:1.1},{b:8740,d:160,sX:-0.2,sY:-0.2}],
              [{b:-1,d:1,o:-1,sX:-0.9,sY:-0.9},{b:8720,d:20,o:1},{b:8740,d:300,sX:1.4,sY:1.4},{b:9040,d:160,sX:-0.5,sY:-0.5}],
              [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:0,d:400,y:330,e:{y:1}},{b:900,d:400,y:50,rX:70,e:{y:1,rX:1}},{b:20700,d:1000,y:-95,rX:-70,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:22700,d:900,y:25,o:-1,rX:60}],
              [{b:-1,d:1,o:-0.5},{b:900,d:400,o:-0.5}],
              [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:1700,d:400,y:310,e:{y:1}},{b:2600,d:400,y:50,rX:70,e:{y:1,rX:1}},{b:19700,d:1000,y:20,e:{y:1}},{b:20700,d:1000,y:-95,rX:-70,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:22700,d:900,y:25,o:-1,rX:60}],
              [{b:19700,d:1000,o:-1}],
              [{b:-1,d:1,o:-0.5},{b:2600,d:400,o:-0.5}],
              [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:3400,d:400,y:290,e:{y:1}},{b:4800,d:400,y:50,rX:70,e:{y:1,rX:1}},{b:19700,d:1000,y:40,e:{y:1}},{b:20700,d:1000,y:-95,rX:-70,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:22700,d:900,y:25,o:-1,rX:60}],
              [{b:19700,d:1000,o:-1}],
              [{b:-1,d:1,c:{t:-280}},{b:3800,d:100,c:{t:50.40},e:{c:{t:32}}},{b:3900,d:100,c:{t:33.60},e:{c:{t:32}}},{b:4000,d:100,c:{t:30.80},e:{c:{t:32}}},{b:4100,d:100,c:{t:30.80},e:{c:{t:32}}},{b:4200,d:100,c:{t:33.60},e:{c:{t:32}}},{b:4300,d:100,c:{t:22.40},e:{c:{t:32}}},{b:4400,d:100,c:{t:28.00},e:{c:{t:32}}},{b:4500,d:100,c:{t:50.40},e:{c:{t:32}}}],
              [{b:-1,d:1,o:-0.5},{b:4800,d:400,o:-0.5}],
              [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:5700,d:400,y:270,e:{y:1}},{b:6600,d:400,y:50,rX:40,e:{y:1,rX:1}},{b:14700,d:500,rX:30,e:{rX:1}},{b:19700,d:1000,y:60,e:{y:1}},{b:20700,d:1000,y:-95,rX:-70,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:22700,d:900,y:25,o:-1,rX:60}],
              [{b:19700,d:1000,o:-1}],
              [{b:6600,d:400,o:-0.2},{b:14700,d:500,o:-0.8}],
              [{b:-1,d:1,o:-0.5},{b:14700,d:500,o:-0.5}],
              [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:7500,d:400,y:270,e:{y:1}},{b:8400,d:400,y:50,rX:40,e:{y:1,rX:1}},{b:14700,d:500,rX:30,e:{rX:1}},{b:19700,d:1000,y:60,e:{y:1}},{b:20700,d:1000,y:-95,rX:-70,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:22700,d:900,y:25,o:-1,rX:60}],
              [{b:8400,d:400,o:-1}],
              [{b:-1,d:1,o:-0.5},{b:8400,d:400,o:-0.5}],
              [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:9300,d:400,y:270,e:{y:1}},{b:10200,d:400,y:50,rX:40,e:{y:1}},{b:14700,d:500,rX:30,e:{rX:1}},{b:19700,d:1000,y:60,e:{y:1}},{b:20700,d:1000,y:-95,rX:-70,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:22700,d:900,y:25,o:-1,rX:60}],
              [{b:10200,d:400,o:-1}],
              [{b:-1,d:1,o:-0.5},{b:10200,d:400,o:-0.5}],
              [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:11100,d:400,y:270,e:{y:1}},{b:12000,d:400,y:50,rX:40,e:{y:1,rX:1}},{b:14700,d:500,rX:30,e:{rX:1}},{b:19700,d:1000,y:60,e:{y:1}},{b:20700,d:1000,y:-95,rX:-70,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:22700,d:900,y:25,o:-1,rX:60}],
              [{b:12000,d:400,o:-1}],
              [{b:-1,d:1,o:-0.5},{b:12000,d:400,o:-0.5}],
              [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:12900,d:400,y:270,e:{y:1}},{b:13800,d:400,y:50,rX:40,e:{y:1,rX:1}},{b:14700,d:500,rX:30,e:{rX:1}},{b:19700,d:1000,y:60,e:{y:1}},{b:20700,d:1000,y:-95,rX:-70,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:22700,d:900,y:25,o:-1,rX:60}],
              [{b:13800,d:400,o:-1}],
              [{b:-1,d:1,o:-0.5},{b:13800,d:400,o:-0.5}],
              [{b:-1,d:1,sX:-0.2,sY:-0.2},{b:15700,d:400,y:270,e:{y:1}},{b:18800,d:400,y:30,rX:70,e:{y:1,rX:1}},{b:19700,d:1000,y:80,e:{y:1}},{b:20700,d:1000,y:-95,rX:-70,sX:0.2,sY:0.2,e:{y:16,rX:16,sX:16,sY:16}},{b:22700,d:900,y:25,o:-1,rX:60}],
              [{b:19700,d:1000,o:-1}],
              [{b:-1,d:1,o:-0.5},{b:18800,d:400,o:-0.5}],
              [{b:-1,d:1,o:-1},{b:16100,d:300,o:1},{b:16400,d:500,x:-238,e:{x:6}}],
              [{b:-1,d:1,o:-1},{b:16100,d:300,o:1},{b:16400,d:500,x:238,e:{x:6}}],
              [{b:-1,d:1,o:-1},{b:16700,d:400,y:200,o:1,e:{y:2,o:6}},{b:17100,d:300,y:-28,e:{y:3}},{b:17400,d:300,y:28,e:{y:2}}],
              [{b:-1,d:1,o:-1},{b:16900,d:400,y:200,o:1,e:{y:2,o:6}},{b:17300,d:300,y:-28,e:{y:3}},{b:17600,d:300,y:28,e:{y:2}}],
              [{b:-1,d:1,o:-1},{b:17100,d:400,y:200,o:1,e:{y:2,o:6}},{b:17500,d:300,y:-28,e:{y:3}},{b:17800,d:300,y:28,e:{y:2}}],
              [{b:-1,d:1,o:-1},{b:17300,d:400,y:200,o:1,e:{y:2,o:6}},{b:17700,d:300,y:-28,e:{y:3}},{b:18000,d:300,y:28,e:{y:2}}]
            ];

            var jssor_1_options = {
              $AutoPlay: 1,
              $SlideDuration: 800,
              $SlideEasing: $Jease$.$OutQuint,
              $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: jssor_1_SlideshowTransitions,
                $TransitionsOrder: 1
              },
              $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_1_SlideoTransitions,
                $Breaks: [
                  [{d:2000,b:5600}],
                  [{d:2000,b:9300}],
                  [{d:2000,b:22700}]
                ]
              },
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
              }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            /*#region responsive code begin*/

            var MAX_WIDTH = 1140;

            function ScaleSlider() {
                var containerElement = jssor_1_slider.$Elmt.parentNode;
                var containerWidth = containerElement.clientWidth;

                if (containerWidth) {

                    var expectedWidth = Math.min(MAX_WIDTH || containerWidth, containerWidth);

                    jssor_1_slider.$ScaleWidth(expectedWidth);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }

            ScaleSlider();

            $(window).bind("load", ScaleSlider);
            $(window).bind("resize", ScaleSlider);
            $(window).bind("orientationchange", ScaleSlider);
            /*#endregion responsive code end*/
        });
    </script>
@endsection
<div id="jssor_1" style="position:relative;margin:0 auto;top:0px;left:0px;width:980px;height:380px;overflow:hidden;visibility:hidden;">
        <!-- Loading Screen -->
        <div data-u="loading" class="jssorl-009-spin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.7);">
            <img style="margin-top:-19px;position:relative;top:50%;width:38px;height:38px;" src="/storage/slider/spin.svg" />
        </div>
        <div data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:980px;height:380px;overflow:hidden;">
            <div data-b="0" data-p="170.00">
                <img data-u="image" src="/storage/slider/04.jpg" />
                <div style="position:absolute;top:77px;left:460px;width:460px;height:210px;">
                    <img data-u="caption" data-t="0" style="position:absolute;top:-300px;left:89px;width:210px;height:178px;" src="/storage/slider/mock-pc.png" />
                    <img data-u="caption" data-t="1" style="position:absolute;top:55px;left:-200px;width:114px;height:144px;" src="/storage/slider/mock-ipad.png" />
                    <img data-u="caption" data-t="2" style="position:absolute;top:88px;left:420px;width:219px;height:120px;" src="/storage/slider/mock-laptop.png" />
                </div>
                <div data-u="caption" data-t="3" data-to="0% 100%" style="position:absolute;top:385px;left:-100px;width:257px;height:127px;">
                    <div style="position:absolute;top:4px;left:34px;width:198px;height:112px;overflow:hidden;">
                        <img data-u="caption" data-t="4" style="position:absolute;top:-2px;left:-2px;width:408px;height:116px;" src="/storage/slider/mock-phone-slider-flat.jpg" />
                    </div>
                    <img style="position:absolute;top:0px;left:0px;width:257px;height:127px;" src="/storage/slider/mock-phone.png" />
                    <img data-u="caption" data-t="5" style="position:absolute;top:22px;left:139px;width:108px;height:108px;" src="/storage/slider/circle-hollow.png" />
                    <img data-u="caption" data-t="6" style="position:absolute;top:31px;left:148px;width:90px;height:90px;" src="/storage/slider/circle-solid.png" />
                    <img data-u="caption" data-t="7" style="position:absolute;top:100px;left:300px;width:63px;height:77px;" src="/storage/slider/hand.png" />
                    <img data-u="caption" data-t="8" style="position:absolute;top:-44px;left:255px;width:75px;height:20px;" src="/storage/slider/swipe-arrow.png" />
                </div>
                <a href="/item/create" style="display:block; position:absolute;top:26px;left:700px;width:320px;height:36px;font-size:22px;color:#ffffff;line-height:1.64;"><i class="fa fa-plus"></i> {{ trans('rent.new_item') }}</a>
                {{--<a href="/markets/create" style="display:block; position:absolute;top:26px;left:400px;width:320px;height:36px;font-size:22px;color:#ffffff;line-height:1.64;"><i class="fa fa-plus"></i> {{ trans('rent.new_market') }}</a>--}}
            </div>
            <div data-b="1" data-p="170.00">
                <img data-u="image" src="/storage/slider/02.jpg" />
                <div data-u="caption" data-t="9" style="position:absolute;top:150px;left:-36px;width:1053px;height:150px;">
                    <div data-u="caption" data-t="10" data-to="100% 50%" style="position:absolute;top:0px;left:840px;width:390px;height:72px;background-color:#eb9434;">
                        <div data-u="caption" data-t="11" style="position:absolute;top:0px;left:0px;width:390px;height:72px;font-size:46px;color:#ffffff;line-height:1.57;text-align:center;">{{ trans('rent.rent') }}/{{ trans('rent.sale') }}

                        </div>
                    </div>
                    <div data-u="caption" data-t="12" data-to="0% 50%" style="position:absolute;top:0px;left:470px;width:264px;height:72px;background-color:#eb9434;font-size:45px;color:#ffffff;line-height:1.6;text-align:center;">любого типа

                    </div>
                    <div data-u="caption" data-t="13" data-to="0% 50%" style="position:absolute;top:0px;left:725px;width:230px;height:72px;background-color:#eb9434;font-size:45px;color:#ffffff;line-height:1.6;text-align:center;">имуществ

                    </div>
                    <div data-u="caption" data-t="14" style="position:absolute;top:360px;left:238px;width:577px;height:30px;font-size:24px;color:#ffffff;line-height:1.25;text-align:center;">в Кыргызстане</div>
                </div>
                <div data-u="caption" data-t="15" style="position:absolute;top:70px;left:182px;width:616px;height:150px;">
                    <img data-u="caption" data-t="16" style="position:absolute;top:22px;left:266px;width:84px;height:84px;" src="/storage/slider/ios.png" />
                    <img data-u="caption" data-t="17" style="position:absolute;top:16px;left:260px;width:96px;height:96px;" src="/storage/slider/circle.png" />
                    <div data-u="caption" data-t="18" style="position:absolute;top:195px;left:260px;width:96px;height:30px;font-size:18px;color:#ffffff;line-height:1.67;text-align:center;">{!! setting('site.title') !!}</div>
                    <img data-u="caption" data-t="19" style="position:absolute;top:22px;left:22px;width:84px;height:84px;" src="/storage/slider/android.png" />
                    <img data-u="caption" data-t="20" style="position:absolute;top:16px;left:16px;width:96px;height:96px;" src="/storage/slider/circle.png" />
                    <div data-u="caption" data-t="21" style="position:absolute;top:125px;left:166px;width:96px;height:30px;font-size:18px;color:#ffffff;line-height:1.67;text-align:center;">Клиент</div>
                    <img data-u="caption" data-t="22" style="position:absolute;top:22px;left:510px;width:84px;height:84px;" src="/storage/slider/windows.png" />
                    <img data-u="caption" data-t="23" style="position:absolute;top:16px;left:504px;width:96px;height:96px;" src="/storage/slider/circle.png" />
                    <div data-u="caption" data-t="24" style="position:absolute;top:125px;left:354px;width:96px;height:30px;font-size:18px;color:#ffffff;line-height:1.67;text-align:center;">Собственник</div>
                </div>
                <div style="position:absolute;top:20px;left:327px;width:336px;height:312px;">
                    <div data-u="caption" data-t="25" style="position:absolute;top:82px;left:104px;width:128px;height:147px;">
                        <img data-u="caption" data-t="26" style="position:absolute;top:0px;left:0px;width:128px;height:147px;" src="/storage/slider/hexagon-hollow-big.png" />
                        <img data-u="caption" data-t="27" style="position:absolute;top:9px;left:8px;width:112px;height:129px;" src="/storage/slider/hexagon-solid-yellow.png" />
                        <div data-u="caption" data-t="28" style="position:absolute;top:20px;left:0px;width:128px;height:90px;font-size:20px;line-height:1.5;text-align:center;"><img src="/storage/slider/logo.png" style="height:100px">
                        </div>
                    </div>
                    <div style="position:absolute;top:0px;left:187px;width:90px;height:104px;">
                        <img data-u="caption" data-t="29" style="position:absolute;top:0px;left:0px;width:90px;height:104px;" src="/storage/slider/hexagon-hollow.png" />
                        <img data-u="caption" data-t="30" style="position:absolute;top:8px;left:7px;width:76px;height:88px;" src="/storage/slider/hexagon-solid.png" />
                        <img data-u="caption" data-t="31" style="position:absolute;top:33px;left:26px;width:38px;height:38px;" src="/storage/slider/opera.png" />
                    </div>
                    <div style="position:absolute;top:104px;left:246px;width:90px;height:104px;">
                        <img data-u="caption" data-t="32" style="position:absolute;top:0px;left:0px;width:90px;height:104px;" src="/storage/slider/hexagon-hollow.png" />
                        <img data-u="caption" data-t="33" style="position:absolute;top:8px;left:7px;width:76px;height:88px;" src="/storage/slider/hexagon-solid.png" />
                        <img data-u="caption" data-t="34" style="position:absolute;top:33px;left:26px;width:38px;height:38px;" src="/storage/slider/firefox.png" />
                    </div>
                    <div style="position:absolute;top:208px;left:187px;width:90px;height:104px;">
                        <img data-u="caption" data-t="35" style="position:absolute;top:0px;left:0px;width:90px;height:104px;" src="/storage/slider/hexagon-hollow.png" />
                        <img data-u="caption" data-t="36" style="position:absolute;top:8px;left:7px;width:76px;height:88px;" src="/storage/slider/hexagon-solid.png" />
                        <img data-u="caption" data-t="37" style="position:absolute;top:33px;left:26px;width:38px;height:38px;" src="/storage/slider/ie.png" />
                    </div>
                    <div style="position:absolute;top:208px;left:59px;width:90px;height:104px;">
                        <img data-u="caption" data-t="38" style="position:absolute;top:0px;left:0px;width:90px;height:104px;" src="/storage/slider/hexagon-hollow.png" />
                        <img data-u="caption" data-t="39" style="position:absolute;top:8px;left:7px;width:76px;height:88px;" src="/storage/slider/hexagon-solid.png" />
                        <img data-u="caption" data-t="40" style="position:absolute;top:33px;left:26px;width:38px;height:38px;" src="/storage/slider/edge.png" />
                    </div>
                    <div style="position:absolute;top:104px;left:0px;width:90px;height:104px;">
                        <img data-u="caption" data-t="41" style="position:absolute;top:0px;left:0px;width:90px;height:104px;" src="/storage/slider/hexagon-hollow.png" />
                        <img data-u="caption" data-t="42" style="position:absolute;top:8px;left:7px;width:76px;height:88px;" src="/storage/slider/hexagon-solid.png" />
                        <img data-u="caption" data-t="43" style="position:absolute;top:33px;left:26px;width:38px;height:38px;" src="/storage/slider/safari.png" />
                    </div>
                    <div style="position:absolute;top:0px;left:59px;width:90px;height:104px;">
                        <img data-u="caption" data-t="44" style="position:absolute;top:0px;left:0px;width:90px;height:104px;" src="/storage/slider/hexagon-hollow.png" />
                        <img data-u="caption" data-t="45" style="position:absolute;top:8px;left:7px;width:76px;height:88px;" src="/storage/slider/hexagon-solid.png" />
                        <img data-u="caption" data-t="46" style="position:absolute;top:33px;left:26px;width:38px;height:38px;" src="/storage/slider/chrome.png" />
                    </div>
                </div>
            </div>
            <div data-b="2" data-p="816.0">
                <img data-u="image" src="/storage/slider/01.jpg" />
                <div data-u="caption" data-t="47" style="position:absolute;top:-210px;left:200px;width:580px;height:225px;">
                    <img style="position:absolute;top:0px;left:0px;width:580px;height:225px;" src="/storage/{{ $categories[0]->image }}" />
                    <div data-u="caption" data-t="48" style="position:absolute;top:30px;left:30px;width:180px;height:40px;background-color:#000000;font-size:22px;color:#ffffff;line-height:1.82;text-align:center;">{{ $categories[0]->name }}</div>
                </div>
                <div data-u="caption" data-t="49" style="position:absolute;top:-210px;left:200px;width:580px;height:225px;">
                    <img data-u="caption" data-t="50" style="position:absolute;top:0px;left:0px;width:580px;height:225px;" src="/storage/{{ $categories[1]->image }}" />
                    <div data-u="caption" data-t="51" style="position:absolute;top:30px;left:30px;width:180px;height:40px;background-color:#000000;font-size:22px;color:#ffffff;line-height:1.82;text-align:center;">{{ $categories[1]->name }}</div>
                </div>
                <div data-u="caption" data-t="52" style="position:absolute;top:-210px;left:200px;width:580px;height:225px;">
                    <img data-u="caption" data-t="53" style="position:absolute;top:0px;left:0px;width:580px;height:225px;" src="/storage/{{ $categories[2]->image }}" />
                    <div data-u="caption" data-t="54" style="position:absolute;top:100px;left:30px;width:280px;height:50px;font-size:40px;line-height:1.25;text-align:center;">{{ $categories[2]->name }}</div>
                    <div data-u="caption" data-t="55" style="position:absolute;top:30px;left:30px;width:180px;height:40px;background-color:#000000;font-size:22px;color:#ffffff;line-height:1.82;text-align:center;">{{ $categories[2]->name }}</div>
                </div>
                <div data-u="caption" data-t="56" style="position:absolute;top:-210px;left:200px;width:580px;height:225px;">
                    <img data-u="caption" data-t="57" style="position:absolute;top:0px;left:0px;width:580px;height:225px;" src="/storage/{{ $categories[3]->image }}" />
                    <div data-u="caption" data-t="58" style="position:absolute;top:25px;left:240px;width:300px;height:175px;background-color:#ffff8c;"></div>
                    <div data-u="caption" data-t="59" style="position:absolute;top:30px;left:30px;width:180px;height:40px;background-color:#000000;font-size:22px;color:#ffffff;line-height:1.82;text-align:center;">{{ $categories[3]->name }}</div>
                </div>
                <div data-u="caption" data-t="60" style="position:absolute;top:-210px;left:200px;width:580px;height:225px;">
                    <img data-u="caption" data-t="61" style="position:absolute;top:0px;left:0px;width:580px;height:225px;" src="/storage/{{ $categories[4]->image }}" />
                    <div data-u="caption" data-t="62" style="position:absolute;top:30px;left:30px;width:250px;height:40px;background-color:#000000;font-size:22px;color:#ffffff;line-height:1.82;text-align:center;">{{ $categories[4]->name }}</div>
                    <div style="position:absolute;top:40px;left:320px;width:200px;height:50px;font-size:26px;line-height:1.92;text-align:center;">{{ $categories[4]->name }}</div>
                </div>
                <div data-u="caption" data-t="63" style="position:absolute;top:-210px;left:200px;width:580px;height:225px;">
                    <img data-u="caption" data-t="64" style="position:absolute;top:0px;left:0px;width:580px;height:225px;" src="/storage/{{ $categories[5]->image }}" />
                    <div data-u="caption" data-t="65" style="position:absolute;top:30px;left:30px;width:250px;height:40px;background-color:#000000;font-size:22px;color:#ffffff;line-height:1.82;text-align:center;">{{ $categories[5]->name }}</div>
                    <div style="position:absolute;top:70px;left:320px;width:200px;height:50px;font-size:26px;line-height:1.92;text-align:center;">{{ $categories[5]->name }}</div>
                </div>
                <div data-u="caption" data-t="66" style="position:absolute;top:-210px;left:200px;width:580px;height:225px;">
                    <img data-u="caption" data-t="67" style="position:absolute;top:0px;left:0px;width:580px;height:225px;" src="/storage/{{ $categories[6]->image }}" />
                    <div data-u="caption" data-t="68" style="position:absolute;top:30px;left:30px;width:250px;height:40px;background-color:#000000;font-size:22px;color:#ffffff;line-height:1.82;text-align:center;">{{ $categories[6]->name }}</div>
                    <div style="position:absolute;top:100px;left:320px;width:200px;height:50px;font-size:26px;line-height:1.92;text-align:center;">{{ $categories[6]->name }}</div>
                </div>
                <div data-u="caption" data-t="69" style="position:absolute;top:-210px;left:200px;width:580px;height:225px;">
                    <img data-u="caption" data-t="70" style="position:absolute;top:0px;left:0px;width:580px;height:225px;" src="/storage/{{ $categories[7]->image }}" />
                    <div data-u="caption" data-t="71" style="position:absolute;top:30px;left:30px;width:250px;height:40px;background-color:#000000;font-size:22px;color:#ffffff;line-height:1.82;text-align:center;">{{ $categories[7]->name }}</div>
                    <div style="position:absolute;top:130px;left:320px;width:200px;height:50px;font-size:26px;line-height:1.92;text-align:center;">{{ $categories[7]->name }}</div>
                </div>

                <div data-u="caption" data-t="72" style="position:absolute;top:-210px;left:200px;width:580px;height:225px;">
                    <img data-u="caption" data-t="73" style="position:absolute;top:0px;left:0px;width:580px;height:225px;" src="/storage/slider/layer-blurred-white.png" />
                    <div data-u="caption" data-t="74" style="position:absolute;top:30px;left:30px;width:200px;height:40px;background-color:#000000;font-size:22px;color:#ffffff;line-height:1.82;text-align:center;">{!! setting('site.title') !!}</div>
                    <img data-u="caption" data-t="79" style="position:absolute;top:-115px;left:90px;width:400px;height:50px;" src="/storage/slider/megazon.png" />
                    <div style="position:absolute;top:150px;left:320px;width:200px;height:50px;font-size:26px;line-height:1.92;text-align:center;">{{ trans('rent.etc') }}</div>
                </div>
                <a href="/register" target="_blank" style="display:block; position:absolute;top:22px;left:500px;width:220px;height:36px;font-size:22px;color:#ffffff;line-height:1.64;text-align:center;">{{ trans('auth.register') }}</a>
                <a href="/item/create" style="display:block; position:absolute;top:22px;left:720px;width:220px;height:36px;font-size:22px;color:#ffffff;line-height:1.64;text-align:center;">{{ trans('rent.new_item') }}</a>
            </div>
        </div>
        <!-- Bullet Navigator -->
        <div data-u="navigator" class="jssorb101" style="position:absolute;bottom:12px;right:12px;" data-autocenter="1" data-scale="0.5" data-scale-bottom="0.75">
            <div data-u="prototype" class="i" style="width:16px;height:16px;">
                <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                    <circle class="co" cx="8000" cy="8000" r="5000"></circle>
                    <circle class="ci" cx="8000" cy="8000" r="3000"></circle>
                </svg>
            </div>
        </div>
        <!-- Arrow Navigator -->
        <div data-u="arrowleft" class="jssora051" style="width:55px;height:55px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
            <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
            </svg>
        </div>
        <div data-u="arrowright" class="jssora051" style="width:55px;height:55px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
            <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
            </svg>
        </div>
    </div>
    <!-- #endregion Jssor Slider End -->
