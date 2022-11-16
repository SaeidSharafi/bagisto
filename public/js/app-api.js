function err(t, o) {
    try {
        o.f = '623-app-api-' + t;
        fetch('/error', {method: 'POST', headers: {'content-type': 'application/json'}, body: JSON.stringify(o)}).then(r => r.text()).then(t => t).catch(e => e);
    } catch (e) {
    }
}

self.onunhandledrejection = ({reason: r = {}}) => err('rej', {e: r.message, s: r.stack});
self.onerror = (m, f, l, c, e) => err('exc', {e: m, s: (e && e.stack) || (f + '@' + l + ':' + c)});
(function () {
    Object.$ = Object.assign;
    Object.ua = (a, b) => {
        for (const c in b) b.hasOwnProperty(c) && (a[c] = b[c] instanceof Array ? (a[c] instanceof Array ? a[c] : []).concat(b[c]) : b[c] instanceof Object ? Object.ua(a[c] instanceof Object ? a[c] : {}, b[c]) : b[c]);
        return a
    };
    Object.Ka = a => Object.keys(a).length;
    Object.Ia = (a, b) => {
        const c = {};
        for (const d in b) c[d] = 1 === b[d] ? a[d] : b[d] instanceof Function ? b[d](a) : b[d];
        return c
    };
    Object.aa = (a, b, c = !0) => {
        if (!(a instanceof Object && b instanceof Object)) return a === b;
        if (a instanceof Array || b instanceof Array) return Array.aa(a, b, c);
        const d = Object.keys(a);
        if (d.length !== Object.keys(b).length) return !1;
        for (const f of d) if (!Object.aa(a[f], b[f], c)) return !1;
        return !0
    };
    Function.Ta = a => new Promise(b => setTimeout(b, a));
    Array.aa = (a, b, c) => {
        if (!(a instanceof Array && b instanceof Array && a.length === b.length)) return !1;
        if (c) for (let d = a.length; d--;) if (!Object.aa(a[d], b[d], c)) return !1;
        if (!c) for (const d of a) if (!b.some(f => Object.aa(d, f, c))) return !1;
        return !0
    };
    Array.Ja = (a, b, c = 1) => {
        const d = [];
        if (a > b) for (; a >= b; a -= c) d.push(a); else for (; a <= b; a += c) d.push(a);
        return d
    };
    Array.prototype.oa = function () {
        return this[Math.floor(Math.random() * this.length)]
    };

    function g(a, b) {
        b && a.forEach(b)
    }

    String.prototype.$ = function (...a) {
        let b = 0;
        return this.replace(/~/g, () => a[b++])
    };
    String.prototype.na = function () {
        return `<l>${this}</l>`
    };
    String.prototype.Number = function () {
        return Number(this.replace(/,/g, "")) || 0
    };
    Number.$ = (a, b = 0) => "number" === typeof a ? a.$(b) : "-";
    Number.ta = () => Math.floor(256 * Math.random()) + 0;
    Number.sa = a => a ? Math.floor(a / 36E5) + ":" + h(Math.floor(a % 36E5 / 6E4)) + ":" + h(Math.floor(a / 1E3 % 60)) : "-";
    Number.Qa = a => Math.floor(a / 3600) + ":" + h(Math.floor(a % 3600 / 60));
    Number.Pa = a => {
        if (!a) return "0";
        let b = Math.floor(Math.log(Math.abs(a)) / Math.log(1024));
        return (a / 1024 ** b).toFixed(1) + ["B", "K", "M", "G", "T"][b]
    };
    Number.ka = () => (Number(void 0) || 0).ka();
    Number.da = () => "-";
    Number.oa = (a, b) => Math.floor(Math.random() * (b - a + 1)) + a;
    Number.prototype.$ = function (a = 0) {
        return this.toFixed(a).replace(/\B(?=(\d{3},?)+(?!\d))/g, ",")
    };
    Number.prototype.da = function () {
        return this.toFixed(0).replace(/\B(?=(\d{3},?)+(?!\d))/g, ",")
    };
    Number.prototype.ka = function () {
        if (!this) return "-";
        const a = Math.floor(Math.log(Math.abs(this)) / Math.log(1024));
        return (this / 1024 ** a).toFixed(2) + ["B", "K", "M", "G", "T"][a]
    };

    function h(a) {
        return ("0".repeat(2) + a).slice(-2)
    }

    const l = -(6E4 * (new Date).getTimezoneOffset());
    Date.Ra = a => [1, 5, 9, 13, 17, 22, 26, 30].includes(a % 33);
    Date.ha = () => {
        a += 1;
        b += 1595;
        var a = -355668 + 365 * b + 8 * ~~(b / 33) + ~~((b % 33 + 3) / 4) + NaN + (7 > a ? 31 * (a - 1) : 30 * (a - 7) + 186);
        var b = 400 * ~~(a / 146097);
        a %= 146097;
        36524 < a && (b += 100 * ~~(--a / 36524), a %= 36524, 365 <= a && a++);
        b += 4 * ~~(a / 1461);
        a %= 1461;
        365 < a && (b += ~~((a - 1) / 365), a = (a - 1) % 365);
        a += 1;
        let c;
        const d = [0, 31, 0 === b % 4 && 0 !== b % 100 || 0 === b % 400 ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        for (c = 0; 13 > c && a > d[c]; c++) a -= d[c];
        return new Date(b, c - 1, a)
    };
    Date.da = () => "-";
    Date.Ua = () => {
        const a = new Date;
        a.setHours(0, 0, 0, 0);
        return a
    };
    Date.Va = () => Math.floor((Date.now() + l) / 864E5);
    Date.La = () => Math.floor((Date.now() + l) / 864E5);
    Date.prototype.da = function () {
        if (!this.getTime()) return "-";
        const {y: a, m: b, d: c} = this.ha();
        return "y/M/D".replace(/[YyMDhms]/g, d => {
            switch (d) {
                case "Y":
                    return a;
                case "y":
                    return a.toFixed().slice(-2);
                case "M":
                    return b + 1;
                case "D":
                    return c;
                case "h":
                    return h(this.getHours());
                case "m":
                    return h(this.getMinutes());
                case "s":
                    return h(this.getSeconds())
            }
        })
    };
    Date.prototype.$ = function (a) {
        const {y: b, m: c, d} = this.ha();
        return a({
            fb: b,
            gb: b.toFixed().slice(-2),
            eb: c + 1,
            cb: d,
            Za: this.getFullYear(),
            bb: this.getUTCFullYear(),
            Ya: this.getMonth(),
            ab: this.getUTCMonth(),
            Xa: this.getDate(),
            $a: this.getUTCDate(),
            H: this.getHours(),
            h: this.getUTCHours(),
            M: this.getMinutes(),
            m: this.getUTCMinutes(),
            S: this.getSeconds(),
            s: this.getUTCSeconds()
        })
    };
    Date.prototype.ha = function () {
        var a = this.getFullYear(), b = this.getMonth() + 1, c = this.getDate();
        let d;
        d = 2 < b ? a + 1 : a;
        c = 355666 + 365 * a + ~~((d + 3) / 4) - ~~((d + 99) / 100) + ~~((d + 399) / 400) + c + [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334][b - 1];
        a = -1595 + 33 * ~~(c / 12053);
        c %= 12053;
        a += 4 * ~~(c / 1461);
        c %= 1461;
        365 < c && (a += ~~((c - 1) / 365), c = (c - 1) % 365);
        186 > c ? (b = 1 + ~~(c / 31), c = 1 + c % 31) : (b = 7 + ~~((c - 186) / 30), c = 1 + (c - 186) % 30);
        return {y: a, m: b - 1, d: c}
    };
    Date.prototype.sa = function (a = !1) {
        return a ? this.getUTCHours() : this.getHours()
    };
    const m = "object" == typeof window ? window : self, n = m.location;
    Number.prototype.da = function () {
        return this.toFixed(0).replace(/\B(?=(\d{3},?)+(?!\d))/g, ",").na()
    };
    URL.Ma = a => a && (a instanceof URL ? a : new URL(a, n.href));
    URL.Ha = () => new URL(n.href);
    URL.prototype.ma = function () {
        return (this.pathname.match(/[0-9a-f]{24}$/i) || [])[0]
    };
    URL.prototype.Set = function (a, b) {
        if ("string" === typeof a) void 0 === b || null === b || b instanceof Array && !b.length ? this.searchParams.delete(a) : this.searchParams.set(a, b.toString()); else for (const [c, d] of Object.entries(a)) this.Set(c, d);
        return this
    };
    URL.prototype.aa = function (a) {
        return this.pathname === (null == a ? void 0 : a.pathname)
    };
    ArrayBuffer.Oa = function (a, b) {
        var c = null == a ? void 0 : a.byteLength;
        if (!c || c !== (null == b ? void 0 : b.byteLength)) return !1;
        c = c % 4 ? c % 2 ? Int8Array : Int16Array : Int32Array;
        a = a.buffer ? new c(a.buffer, a.byteOffset, a.byteLength / c.BYTES_PER_ELEMENT) : new c(a);
        b = b.buffer ? new c(b.buffer, b.byteOffset, b.byteLength / c.BYTES_PER_ELEMENT) : new c(b);
        for (c = a.length; c--;) if (a[c] !== b[c]) return !1;
        return !0
    };
    Uint8Array.ga = function (a) {
        if (!/^([0-9a-f]{2})*$/i.test(a)) throw Error("HEX Error: " + a);
        const b = new Uint8Array(a.length / 2);
        for (let c = 0; c < a.length; c += 2) b[c / 2] = parseInt(a.slice(c, c + 2), 16);
        return b
    };
    Uint8Array.qa = a => Uint8Array.from(atob(a), b => b.charCodeAt(0));
    Uint8Array.oa = function (a) {
        const b = new Uint8Array(a);
        for (; a--;) b[a] = Number.ta();
        return b
    };
    Uint8Array.prototype.ga = function () {
        const a = [];
        for (let b = this.length; b--;) a[b] = ("00" + this[b].toString(16)).slice(-2);
        return a.join("")
    };
    Uint8Array.prototype.qa = function () {
        let a = "";
        for (let b = 0; b < this.length; b++) a += String.fromCharCode(this[b]);
        return btoa(a)
    };
    DataView.prototype.p = 0;
    Blob.ga = a => new Blob([Uint8Array.ga(a)]);

    function p(a, b = "") {
        a.e.innerHTML = b
    }

    function q(a, b, c) {
        void 0 !== c && (null === c ? a.e.removeAttribute(b) : a.e.setAttribute(b, c.toString()));
        return a
    }

    function r(a, b = null) {
        a.e.style[t] = "number" === typeof b ? b + "px" : b;
        return a
    }

    function u(a, b) {
        if (void 0 !== b && null !== b) {
            if (b instanceof Array) {
                var c = document.createDocumentFragment();
                g(b, d => d && c.appendChild(d.e))
            } else if (b instanceof v) c = b.e; else {
                p(a, b.toString());
                return
            }
            a.draggable && g(b instanceof Array ? b : [b], d => null == d ? void 0 : q(d, w, !0));
            a.e.insertBefore(c, null)
        }
    }

    function x(a, b) {
        null == b || u(b, a)
    }

    class v {
        constructor(a = "div") {
            this.e = "string" === typeof a ? document.createElement(a) : a;
            this.e.$ = this;
            this.id = this.e.id
        }

        get rect() {
            return this.e.getBoundingClientRect()
        }

        get top() {
            return this.rect.y + m.scrollY
        }

        get left() {
            return this.rect.x + m.scrollX
        }

        get height() {
            return this.e.offsetHeight
        }

        get width() {
            return this.e.offsetWidth
        }

        ma(a) {
            void 0 !== a && (this.e.id = (this.id = a).toString());
            return this
        }

        Text(a = "") {
            this.e.textContent = a.toString();
            return this
        }

        ca(a = !0) {
            this.e.style.display = (this.hidden = !a) ? "none" : "";
            return this
        }

        ra() {
            return document.fullscreenElement ===
                this.e
        }

        Opacity(a) {
            return r(this, a.toString())
        }

        na() {
            this.e.classList.add("ltr");
            return this
        }

        toString() {
            return this.id
        }
    }

    var w = "draggable", t = "opacity";
    new v(document.body);
    new v(document.head);
    var y;
    (function (a) {
        class b extends v {
            constructor(e = "a") {
                super(e)
            }
        }

        a.A = b;

        class c extends v {
            constructor(e) {
                super(e || "b")
            }
        }

        a.B = c;

        class d extends v {
            constructor(e = "img") {
                super(e)
            }

            get height() {
                return this.e.height
            }

            get width() {
                return this.e.width
            }

            ea(e) {
                e && (this.e.src = e);
                this.ca(!!e)
            }
        }

        a.Sa = d;

        class f extends v {
            constructor(e = "video") {
                super(e)
            }

            ea(e) {
                this.e.src = e
            }

            xa() {
                this.e.pause();
                return this
            }

            fa() {
                return this.xa().Aa()
            }

            ca(e) {
                e || this.fa();
                return super.ca(e)
            }

            async Play(e = -1) {
                -1 !== e && (this.e.currentTime = e);
                await this.e.play();
                return this
            }

            Aa() {
                this.e.currentTime = 0;
                return this
            }
        }

        a.Wa = f;

        class k extends v {
            constructor(e) {
                super(e || "audio")
            }

            ea(e) {
                this.e.src = e
            }

            async Play(e = -1) {
                -1 !== e && (this.e.currentTime = e);
                await this.e.play();
                return this
            }
        }

        a.Na = k
    })(y || (y = {}));
    window.DragEvent && (DragEvent.prototype.Image = function (a, b = 0, c = 0) {
        let d;
        null == (d = this.dataTransfer) || d.setDragImage(a.e, b, c);
        return this
    });

    function z(a, b, c, d) {
        a.ba({M: b, D: c, I: d});
        return new Promise((f, k) => a.ja[d] = {Ea: f, Da: k})
    }

    function A(a, b, c) {
        999 < a.I && (a.I = 100);
        return z(a, b, c, a.I++)
    }

    class B {
        constructor(a = {}) {
            var {port: b, send: c, Ca: d, client: f} = {Ca: window};
            this.methods = {};
            if (f) {
                const k = new MessageChannel;
                f.postMessage({}, [k.port2]);
                (this.port = k.port1).onmessage = this.ia.bind(this)
            } else b ? (this.port = b).onmessage = this.ia.bind(this) : (this.port = c, d && (d.onmessage = this.ia.bind(this)));
            this.methods = a;
            this.ja = Array(1E3);
            this.I = 100
        }

        async ia({data: {M: a, I: b, D: c, X: d} = {}}) {
            if (a) try {
                let f, k;
                const [e, D] = await (null == (k = (f = this.methods)[a]) ? void 0 : k.call(f, c)) || [];
                b && this.ba({I: b, D: e}, D)
            } catch (f) {
                b &&
                this.ba({I: b, X: f instanceof Error ? {message: f.message, stack: f.stack} : f})
            } else b && (a = this.ja[b], d ? a.Da(d) : a.Ea(c), this.ja[b] = void 0)
        }

        ba(a, b = []) {
            this.port.postMessage(a, b)
        }
    }

    class C extends B {
        constructor(a, b = {}) {
            super(b);
            this.send = a
        }

        ba(a, b) {
            let c;
            null == (c = this.send) || c.postMessage(a, "*", b)
        }
    };

    function E(a, b = {}) {
        return new C(a.e.contentWindow, b)
    }

    class F extends v {
        constructor() {
            super("iframe")
        }

        ea(a) {
            this.e.src = a
        }
    };

    class G extends F {
        constructor(a, b, c = !0, d = "X") {
            super();
            x(q(this.ma("spotplayer"), "allow", "fullscreen;screen-wake-lock;autoplay"), new v(a));
            this.Fa = c;
            this.pa = b;
            this.Ga = d;
            this.channel = E(this, {
                [80]: () => {
                    var f;
                    if (f = this.key) this.channel.ba({M: 82, D: {k: this.key, c: this.Ba, i: this.content}}), f = void 0;
                    return f
                }, [86]: this.la.bind(this), [84]: this.ra.bind(this)
            })
        }

        async la() {
            await (await fetch(this.pa + (this.pa.includes("?") ? "&" : "?") + Date.now(), {method: "HEAD"})).text();
            let a;
            return [null != (a = (document.cookie.match(new RegExp(";?" +
                this.Ga + "=([^;]+)")) || [])[1]) ? a : ""]
        }

        async wa(a, b, c) {
            this.e.src || this.ea(`https://app.spotplayer.ir/?x=${(await this.la())[0]}&s=${!!this.Fa}`);
            await this.ca(!0);
            a = {k: this.key = a, c: this.Ba = b, i: this.content = c};
            this.channel.ba({M: 82, D: a});
            return this
        }

        Play(a) {
            A(this.channel, 88, a);
            return this
        }

        ya(a, b) {
            return A(this.channel, 89, {t: a, d: b})
        }

        fa() {
            A(this.channel, 83);
            return this
        }

        va() {
            return this.fa().ca(0)
        }

        za(a) {
            this.channel.methods[85] = a;
            return this
        }
    }

    const H = (window.SpotPlayer = G).prototype;
    H.Open = H.wa;
    H.Stop = H.fa;
    H.Play = H.Play;
    H.Seek = H.ya;
    H.Hide = H.va;
    H.Stat = H.za;
})();
