var Modal = new Class({
    id: null,
    selector: null,
    url: null,
    params: null,
    success: null,
    loading: false,
    fullscreen: false,
    setLoading: function (loading) {
        this.loading = loading;
    },
    initialize: function (url) {
        if (Default.isEmpty(url)) this.url = url;
        this.create();
        this.autoload();
    },
    autoload: function () {
        var self = this;
        jQuery("body")
            // Fecha modal
            .delegate(".modal-close", "click", function () {
                if (jQuery(this).data("reload")) {
                    var url = jQuery(this).data("url");
                    if (url) {
                        location.href = url;
                    } else {
                        window.location.reload();
                    }
                }
                self.closeAll();
                jQuery("body").removeClass("modal-open");
            })
            // Fecha modal e carrega pagina definida
            .delegate(".modal-load-url", "click", function (e) {
                e.preventDefault();

                if (!Default.isEmpty(jQuery(this).data("url"))) {
                    var _url = jQuery(this).data("url");
                } else {
                    var _url = jQuery(this).attr("href");
                }

                var _id = jQuery(this).data("id");
                jQuery(this).closest(".modal-ajax").remove();

                var modal = new Modal();
                var params = { id: _id };
                modal.setParams(params);
                modal.setUrl(_url);
                modal.execute();
            })
            // Envia formulario
            .delegate(".modal-submit", "click", function () {
                var modal = jQuery(this).closest(".modal");
                if (modal.find("form").length) {
                    modal.find("form").submit();
                } else {
                    console.log("Modal: Não foi encontrado um formulário.");
                }
            });
        // Fecha o modal com a tecla 'Esc'
        jQuery(document).keydown(function (e) {
            if (e.keyCode == 27 || e.keyWhich == 27) {
                self.closeAll();
            }
        });
    },
    isOpen: function () {
        if (jQuery(this.selector).css("display") != "none") return true;
        else return false;
    },
    calculationHeight: function () {
        var windowHeight = jQuery(window).height();
        var modalHeight = jQuery(this.selector).find(".modal-content").height();
        var finalHeight = windowHeight - modalHeight + 400 - 100;
        jQuery(this.selector)
            .find(".modal-body")
            .css("max-height", finalHeight + "px");

        this.$element = jQuery(this.selector);
        this.$content = this.$element.find(".modal-content");
        var borderWidth =
            this.$content.outerHeight() - this.$content.innerHeight();
        var dialogMargin = $(window).width() < 768 ? 20 : 60;
        var contentHeight =
            jQuery(window).height() - (dialogMargin + borderWidth);
        var headerHeight =
            this.$element.find(".modal-header").outerHeight() || 0;
        var footerHeight =
            this.$element.find(".modal-footer").outerHeight() || 0;
        var maxHeight = contentHeight - (headerHeight + footerHeight);

        this.$content.css({
            overflow: "hidden",
        });

        this.$element.find(".modal-body").css({
            "max-height": maxHeight,
            "overflow-y": "auto",
        });
    },
    setFullscreen: function (boolean) {
        if (!Default.isEmpty(boolean) && boolean == true) {
            jQuery(this.selector).find(".modal-dialog").addClass("modal-lg");
        }
    },
    create: function () {
        jQuery("body").addClass("modal-open");
        this.id = "modal-" + Math.floor(Math.random() * 10000000 + 1);
        this.selector = "#" + this.id;
        var length = jQuery(".modal").length;

        var div =
                '<div id="' +  this.id +'" class="modal modal-ajax" aria-hidden="true" tabindex="-1" role="dialog">';
        div += '	<div class="modal-dialog" role="document">';
        div += '		<div class="modal-content">';
        div += "		</div>";
        div += "	</div>";
        div += "</div>";
        div += '<div class="modal-backdrop" style="opacity: 0.78 !important;"></div>';

        jQuery("body").append(div);
    },
    close: function () {
        jQuery("body").find(".modal-ajax").remove();
        jQuery("body").find(".modal-backdrop").remove();
        jQuery("body").removeClass("modal-open");
    },
    closeAll: function () {
        jQuery("body").find(".modal-ajax").remove();
        jQuery("body").find(".modal-backdrop").remove();
        jQuery("body").removeClass("modal-open");
    },
    setUrl: function (url) {
        if (!Default.isEmpty(url)) this.url = url;
    },
    setParams: function (params) {
        this.params = params;
    },
    getId: function () {
        return this.selector;
    },
    execute: function () {
        var self = this;

        if (!Default.isEmpty(this.url)) {
            console.log("Modal: Buscando " + this.url);

            if (this.loading) {
                var div = '<div class="modal-header">';
                div +=
                    '    <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>';
                div += '    <h4 class="modal-title">Carregando...</h4>';
                div += "</div>";
                div += '<div class="modal-body text-center">';
                div += '	<div class="loader"></div>';
                div += "</div>";

                jQuery(this.selector).find(".modal-content").html(div);
            }

            var ajax = new Ajax(this.url);

            if (!Default.isEmpty(this.params)) ajax.setParams(this.params);

            ajax.setSuccess(function (callback) {
                jQuery(self.selector).attr("data-url", self.url);
                if (self.isOpen()) {
                    jQuery(self.selector)
                        .find(".modal-content")
                        .fadeOut(200, function () {
                            jQuery(self.selector)
                                .find(".modal-content")
                                .html(callback);
                            jQuery(self.selector)
                                .find(".modal-content")
                                .fadeIn(200);
                            //self.calculationHeight();
                            //alert("oi");
                        });
                } else {
                    jQuery(self.selector).fadeOut(0);
                    jQuery(self.selector).find(".modal-content").html(callback);
                    jQuery(self.selector).fadeIn(200);
                }
            });

            ajax.execute();
        } else {
            console.log("Modal: URL inválida.");
        }
    },
});
