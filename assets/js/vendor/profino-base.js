var Profino = (function() {
    var time = new Date().getTime(),
        o = { app: {}, paths: {}, cache: {}, dbg: {}, mods: {} };

    o.assignModule = function(name, obj) {
        if(typeof o.mods[name] === 'undefined') {
            return o.mods[name] = obj;
        }

        throw new Error('[[error:unable to assign module - '+name+']]');
    };

    // make sure console is always available
    if(!window.console) {
        window.console = {
            log: function() {},
            error: function() {},
            info: function() {}
        }
    }

    // @note disable logging for IE11
    o.dbg.log = function() {};
    o.dbg.error = function() {};
    o.dbg.info = function() {};

    o.dbg.div = function(messages) {
        // make sure messsages is an array
        if(typeof messages.length === 'undefined') {
            messages = [messages];
        }
        var line = '---------------------';
        o.dbg.log('>'+line);

        messages.forEach(function(message) { o.dbg.log(message) });

        return o.dbg.log('<'+line);
    };
    // throwables
    o.dbg.throw = function(type, message) {
        // make first char uppercase
        type = type.charAt(0).toUpperCase() + type.slice(1);
        if(typeof window[type] !== 'undefined') {
            throw new window[type](message);
        }
        return this;
    };
    o.dbg.error = function(m) {
        this.throw.call(this, 'Error', m);
        return this;
    };

    o.app.version = __version;
    o.app.mode = __mode;
    o.app.environment = __environment;

    o.paths = {
        public: '/app/js',
        build: '/dist/js'
    };

    o.cacheOff = true;
    o.cache = {
        bust: __bust,
        date: ''
    };

    o.bb = {
        relative_path: __bb_relative_path
    };

    o.templates = {
        cache: {},
        globals: {}
    };

    // misc functions
    o.fn = {};

    return o;
}()),

// @todo: remove from production
pf_om = {
    mode: Profino.app.mode,
    env: Profino.app.environment
},
// define require object with pre-args
require = {
    baseUrl: Profino.paths.public,
    urlArgs: 'bust=' + Profino.cache.bust
};