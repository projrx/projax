// version: 2018-10-26
    /**
    * o--------------------------------------------------------------------------------o
    * | This file is part of the RGraph package - you can learn more at:               |
    * |                                                                                |
    * |                          http://www.rgraph.net                                 |
    * |                                                                                |
    * | RGraph is licensed under the Open Source MIT license. That means that it's     |
    * | totally free to use and there are no restrictions on what you can do with it!  |
    * o--------------------------------------------------------------------------------o
    */
    
    /**
    * Having this here means that the RGraph libraries can be included in any order, instead of you having
    * to include the common core library first.
    */

    // Define the RGraph global variable
    RGraph = window.RGraph || {isRGraph: true};
    RGraph.Drawing = RGraph.Drawing || {};

    /**
    * The constructor. This function sets up the object. It takes the ID (the HTML attribute) of the canvas as the
    * first argument and the data as the second. If you need to change this, you can.
    * 
    * @param string id    The canvas tag ID
    * @param number x    The X position of the label
    * @param number y    The Y position of the label
    * @param number text The text used - should be a single character (unless you've significantly increased
    *                    the size of the marker.
    */
    RGraph.Drawing.Marker1 = function (conf)
    {
        /**
        * Allow for object config style
        */
        if (   typeof conf === 'object'
            && typeof conf.x === 'number'
            && typeof conf.y === 'number'
            && typeof conf.radius == 'number'
            && typeof conf.id === 'string'
            && typeof conf.text === 'string') {

            var id                        = conf.id,
                canvas                    = document.getElementById(id),
                x                         = conf.x,
                y                         = conf.y,
                radius                    = conf.radius,
                text                      = conf.text,
                parseConfObjectForOptions = true; // Set this so the config is parsed (at the end of the constructor)
        
        } else {
        
            var id     = conf,
                canvas = document.getElementById(id),
                x      = arguments[1],
                y      = arguments[2],
                radius = arguments[3],
                text   = arguments[4];
        }




        // id, x, y, radius, text)
        this.id                = id;
        this.canvas            = canvas;
        this.context           = this.canvas.getContext("2d");
        this.colorsParsed      = false;
        this.canvas.__object__ = this;
        this.original_colors   = [];
        this.firstDraw         = true; // After the first draw this will be false


        /**
        * Store the properties
        */
        this.centerx = x;
        this.centery = y;
        this.radius  = radius;
        this.text    = text;


        /**
        * This defines the type of this shape
        */
        this.type = 'drawing.marker1';


        /**
        * This facilitates easy object identification, and should always be true
        */
        this.isRGraph = true;


        /**
        * This adds a uid to the object that you can use for identification purposes
        */
        this.uid = RGraph.CreateUID();


        /**
        * This adds a UID to the canvas for identification purposes
        */
        this.canvas.uid = this.canvas.uid ? this.canvas.uid : RGraph.CreateUID();


        /**
        * Some example background properties
        */
        this.properties =
        {
            'chart.strokestyle':        'black',
            'chart.fillstyle':          'white',
            'chart.linewidth':          2,
            'chart.text.color':         'black',
            'chart.text.size':          12,
            'chart.text.font':          'Arial, Verdana, sans-serif',
            'chart.text.accessible':           true,
            'chart.text.accessible.overflow':  'visible',
            'chart.text.accessible.pointerevents': false,
            'chart.events.click':       null,
            'chart.events.mousemove':   null,
            'chart.shadow':             true,
            'chart.shadow.color':       '#aaa',
            'chart.shadow.offsetx':     0,
            'chart.shadow.offsety':     0,
            'chart.shadow.blur':       15,
            'chart.highlight.stroke':   'rgba(0,0,0,0)',
            'chart.highlight.fill':     'rgba(255,0,0,0.7)',
            'chart.tooltips':           null,
            'chart.tooltips.highlight': true,
            'chart.tooltips.event':     'onclick',
            'chart.align':              'center',
            'chart.clearto':   'rgba(0,0,0,0)'
        }

        /**
        * A simple check that the browser has canvas support
        */
        if (!this.canvas) {
            alert('[DRAWING.MARKER1] No canvas support');
            return;
        }


        /**
        * Create the dollar object so that functions can be added to them
        */
        this.$0 = {};


        /**
        * Arrays that store the coordinates
        */
        this.coords = [];
        this.coordsText = [];



        /**
        * Translate half a pixel for antialiasing purposes - but only if it hasn't beeen
        * done already
        */
        if (!this.canvas.__rgraph_aa_translated__) {
            this.context.translate(0.5,0.5);

            this.canvas.__rgraph_aa_translated__ = true;
        }





        // Short variable names
        var RG   = RGraph,
            ca   = this.canvas,
            co   = ca.getContext('2d'),
            prop = this.properties,
            pa2  = RG.path2,
            win  = window,
            doc  = document,
            ma   = Math
        
        
        
        /**
        * "Decorate" the object with the generic effects if the effects library has been included
        */
        if (RG.Effects && typeof RG.Effects.decorate === 'function') {
            RG.Effects.decorate(this);
        }




        /**
        * A setter method for setting graph properties. It can be used like this: obj.Set('chart.strokestyle', '#666');
        * 
        * @param name  string The name of the property to set
        * @param value mixed  The value of the property
        */
        this.set =
        this.Set = function (name)
        {
            var value = typeof arguments[1] === 'undefined' ? null : arguments[1];

            /**
            * the number of arguments is only one and it's an
            * object - parse it for configuration data and return.
            */
            if (arguments.length === 1 && typeof name === 'object') {
                RG.parseObjectStyleConfig(this, name);
                return this;
            }




    
            /**
            * This should be done first - prepend the propertyy name with "chart." if necessary
            */
            if (name.substr(0,6) != 'chart.') {
                name = 'chart.' + name;
            }




            // Convert uppercase letters to dot+lower case letter
            while(name.match(/([A-Z])/)) {
                name = name.replace(/([A-Z])/, '.' + RegExp.$1.toLowerCase());
            }





    
            prop[name] = value;
    
            return this;
        };




        /**
        * A getter method for retrieving graph properties. It can be used like this: obj.Get('chart.strokestyle');
        * 
        * @param name  string The name of the property to get
        */
        this.get =
        this.Get = function (name)
        {
            /**
            * This should be done first - prepend the property name with "chart." if necessary
            */
            if (name.substr(0,6) != 'chart.') {
                name = 'chart.' + name;
            }

            // Convert uppercase letters to dot+lower case letter
            while(name.match(/([A-Z])/)) {
                name = name.replace(/([A-Z])/, '.' + RegExp.$1.toLowerCase());
            }
    
            return prop[name.toLowerCase()];
        };




        /**
        * Draws the circle
        */
        this.draw =
        this.Draw = function ()
        {
            /**
            * Fire the onbeforedraw event
            */
            RG.FireCustomEvent(this, 'onbeforedraw');
            
            var r = this.radius;
    
            if (prop['chart.align'] == 'left') {
    
                this.markerCenterx = this.centerx - r - r - 3;
                this.markerCentery = this.centery - r - r - 3;
            
            } else if (prop['chart.align'] == 'right') {
                
                this.markerCenterx = this.centerx + r + r + 3;
                this.markerCentery = this.centery - r - r - 3;
    
            } else {
    
                this.markerCenterx = this.centerx;
                this.markerCentery = this.centery - r - r - 3;
            }
    
            /**
            * Parse the colors. This allows for simple gradient syntax
            */
            if (!this.colorsParsed) {
    
                this.parseColors();
    
                // Don't want to do this again
                this.colorsParsed = true;
            }
            
            
            
            /**
            * Stop this growing uncontrollably
            */
            this.coordsText = [];




            /**
            * DRAW THE MARKER HERE
            */
            pa2(co, ['b','lw',prop['chart.linewidth']]);

            if (prop['chart.shadow']) {
                RG.setShadow(this, prop['chart.shadow.color'], prop['chart.shadow.offsetx'], prop['chart.shadow.offsety'], prop['chart.shadow.blur']);
            }
            this.drawMarker();
            
            pa2(co, ['c','s',prop['chart.strokestyle'],'f',prop['chart.fillstyle']]);




            // Turn the shadow off
            RG.noShadow(this);




            // Now draw the text on the marker
            co.fillStyle = prop['chart.text.color'];
            
            // Draw the text on the marker
            RG.text2(this, {
                font:   prop['chart.text.font'],
                size:   prop['chart.text.size'],
                x:      this.coords[0][0] - 1,
                y:      this.coords[0][1] - 1,
                text:   this.text,
                valign: 'center',
                halign: 'center',
                tag:    'labels'
            });
    
            /**
            * This installs the event listeners
            */
            RG.installEventListeners(this);
    

            /**
            * Fire the onfirstdraw event
            */
            if (this.firstDraw) {
                this.firstDraw = false;
                RG.fireCustomEvent(this, 'onfirstdraw');
                this.firstDrawFunc();
            }




            /**
            * Fire the ondraw event
            */
            RG.fireCustomEvent(this, 'ondraw');
            
            return this;
        };




        /**
        * Used in chaining. Runs a function there and then - not waiting for
        * the events to fire (eg the onbeforedraw event)
        * 
        * @param function func The function to execute
        */
        this.exec = function (func)
        {
            func(this);
            
            return this;
        };




        /**
        * The getObjectByXY() worker method
        */
        this.getObjectByXY = function (e)
        {
            if (this.getShape(e)) {
                return this;
            }
        };




        /**
        * Not used by the class during creating the shape, but is used by event handlers
        * to get the coordinates (if any) of the selected bar
        * 
        * @param object e The event object
        * @param object   OPTIONAL You can pass in the bar object instead of the
        *                          function using "this"
        */
        this.getShape = function (e)
        {
            var mouseXY = RG.getMouseXY(e),
                mouseX  = mouseXY[0],
                mouseY  = mouseXY[1];
    
            /**
            * Path the marker but DON'T STROKE OR FILL it
            */
            co.beginPath();
            this.drawMarker();
    
            if (co.isPointInPath(mouseXY[0], mouseXY[1])) {
    
                return {
                    0: this, 1: this.coords[0][0], 2: this.coords[0][1], 3: this.coords[0][2], 4: 0,
                    'object': this, 'x': this.coords[0][0], 'y': this.coords[0][1], 'radius': this.coords[0][2], 'index': 0, 'tooltip': prop['chart.tooltips'] ? prop['chart.tooltips'][0] : null
                };
            }
            
            return null;
        };




        /**
        * Each object type has its own Highlight() function which highlights the appropriate shape
        * 
        * @param object shape The shape to highlight
        */
        this.highlight =
        this.Highlight = function (shape)
        {
            if (prop['chart.tooltips.highlight']) {
                if (typeof prop['chart.highlight.style'] === 'function') {
                    (prop['chart.highlight.style'])(shape);
                } else {
                    
                    co.beginPath(); 
                    co.strokeStyle = prop['chart.highlight.stroke'];
                    co.fillStyle   = prop['chart.highlight.fill'];
                    this.drawMarker();
                    co.closePath();
                    co.stroke();
                    co.fill();
                }
            }
        };




        /**
        * This function is used to encapsulate the actual drawing of the marker. It
        * intentional does not start a path or set colors.
        */
        this.drawMarker =
        this.DrawMarker = function ()
        {
            var r = this.radius;
            
            if (prop['chart.align'] === 'left') {
    
                var x = this.markerCenterx,
                    y = this.markerCentery;
        
                pa2(co, ['a',x,y,r,RG.HALFPI,RG.TWOPI,false]);
                
                pa2(co, ['qc',x + r,y + r,x + r + r,y + r + r]);
                pa2(co, ['qc',x + r,y + r,x,y + r]);

            } else if (prop['chart.align'] === 'right') {
    
                var x = this.markerCenterx,
                    y = this.markerCentery;

                pa2(co, ['a',x,y,r,RG.HALFPI,RG.PI,true]);
    
               // special case for MSIE 7/8
                pa2(co, ['qc',x - r,y + r,x - r - r,y + r + r]);
                pa2(co, ['qc',x - r, y + r, x, y + r]);
    
            // Default is center
            } else {
    
                var x = this.markerCenterx,
                    y = this.markerCentery;
    
                pa2(co, ['a',x, y, r, RG.HALFPI / 2, RG.PI - (RG.HALFPI / 2), true]);
                
                // special case for MSIE 7/8
                pa2(co, ['qc',x,y + r + (r / 4),x,y + r + r - 2]);
                pa2(co, ['qc',x,y + r + (r / 4),x + (ma.cos(RG.HALFPI / 2) * r),y + (ma.sin(RG.HALFPI / 2) * r)]);
            }

            this.coords[0] = [x, y, r];
        };




        /**
        * This allows for easy specification of gradients
        */
        this.parseColors = function ()
        {
            // Save the original colors so that they can be restored when the canvas is reset
            if (this.original_colors.length === 0) {
                this.original_colors['chart.fillstyle']        = RG.arrayClone(prop['chart.fillstyle']);
                this.original_colors['chart.strokestyle']      = RG.arrayClone(prop['chart.strokestyle']);
                this.original_colors['chart.highlight.fill']   = RG.arrayClone(prop['chart.highlight.fill']);
                this.original_colors['chart.highlight.stroke'] = RG.arrayClone(prop['chart.highlight.stroke']);
                this.original_colors['chart.text.color']       = RG.arrayClone(prop['chart.text.color']);
            }

            /**
            * Parse various properties for colors
            */
            prop['chart.fillstyle']        = this.parseSingleColorForGradient(prop['chart.fillstyle']);
            prop['chart.strokestyle']      = this.parseSingleColorForGradient(prop['chart.strokestyle']);
            prop['chart.highlight.stroke'] = this.parseSingleColorForGradient(prop['chart.highlight.stroke']);
            prop['chart.highlight.fill']   = this.parseSingleColorForGradient(prop['chart.highlight.fill']);
            prop['chart.text.color']       = this.parseSingleColorForGradient(prop['chart.text.color']);
        };




        /**
        * Use this function to reset the object to the post-constructor state. Eg reset colors if
        * need be etc
        */
        this.reset = function ()
        {
        };




        /**
        * This parses a single color value
        */
        this.parseSingleColorForGradient = function (color)
        {
            if (!color || typeof(color) != 'string') {
                return color;
            }
    
            if (color.match(/^gradient\((.*)\)$/i)) {

                // Allow for JSON gradients
                if (color.match(/^gradient\(({.*})\)$/i)) {
                    return RGraph.parseJSONGradient({object: this, def: RegExp.$1});
                }

                // Create the gradient
                var parts = RegExp.$1.split(':'),
                    grad = co.createRadialGradient(this.markerCenterx, this.markerCentery, 0, this.markerCenterx, this.markerCentery, this.radius),
                    diff = 1 / (parts.length - 1);
    
                grad.addColorStop(0, RG.trim(parts[0]));
    
                for (var j=1; j<parts.length; ++j) {
                    grad.addColorStop(j * diff, RG.trim(parts[j]));
                }
            }
    
            return grad ? grad : color;
        };




        /**
        * Using a function to add events makes it easier to facilitate method chaining
        * 
        * @param string   type The type of even to add
        * @param function func 
        */
        this.on = function (type, func)
        {
            if (type.substr(0,2) !== 'on') {
                type = 'on' + type;
            }
            
            if (typeof this[type] !== 'function') {
                this[type] = func;
            } else {
                RG.addCustomEventListener(this, type, func);
            }
    
            return this;
        };




        /**
        * This function runs once only
        * (put at the end of the file (before any effects))
        */
        this.firstDrawFunc = function ()
        {
        };




        /**
        * Objects are now always registered so that the chart is redrawn if need be.
        */
        RG.register(this);




        /**
        * This is the 'end' of the constructor so if the first argument
        * contains configuration data - handle that.
        */
        if (parseConfObjectForOptions) {
            RG.parseObjectStyleConfig(this, conf.options);
        }
    };