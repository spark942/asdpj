var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

/*elixir(function(mix) {
    mix.less('app.less');
});
*/

require('laravel-elixir-spritesmith');

elixir(function(mix) {
  /*mix.spritesmith('resources/assets/img/characters/thum32', {
  	imgName  : 'sprite.png',
  	cssName  : 'sprite.css',
    imgOutput: 'public/img/characters/t32',
    cssOutput: 'public/css/sprites/characters/t32',
    cssVarMap: function (sprite) {
	  sprite.name = 'char32-' + sprite.name;
	}
  });*/

  mix.spritesmith('resources/assets/img/characters/unit60', {
    imgName  : 'sprite.png',
    cssName  : 'sprite.css',
    imgOutput: 'public/img/unit60',
    cssOutput: 'public/css/sprites/unit60',
    cssVarMap: function (sprite) {
    sprite.name = 'unit60-' + sprite.name;
  }
  });
  mix.spritesmith('resources/assets/img/characters/misc', {
    imgName  : 'sprite.png',
    cssName  : 'sprite.css',
    imgOutput: 'public/img/misc',
    cssOutput: 'public/css/sprites/misc',
    cssVarMap: function (sprite) {
    sprite.name = 'misc-' + sprite.name;
  }
  });

  /*mix.spritesmith('resources/assets/img/characters/thum64', {
  	imgName  : 'sprite.png',
  	cssName  : 'sprite.css',
    imgOutput: 'public/img/characters/t64',
    cssOutput: 'public/css/sprites/characters/t64',
    cssVarMap: function (sprite) {
	  sprite.name = 'char64-' + sprite.name;
	}
  });*/
});