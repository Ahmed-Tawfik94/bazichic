<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* home.twig */
class __TwigTemplate_caea7c3986f9b9cd2a6ce837671dedbf extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'jstriggers' => [$this, 'block_jstriggers'],
            'slider' => [$this, 'block_slider'],
            'content' => [$this, 'block_content'],
            'jsbottomincludes' => [$this, 'block_jsbottomincludes'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("layout.twig", "home.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_jstriggers(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 3
        yield "<script type=\"text/javascript\">
 \$(document).ready(function() {
//setTimeout(function(){ 
//alert(\"Bazichic Welcomes You.\");
//showWelcome();
//}, 6200);
});
</script>
";
        yield from [];
    }

    // line 13
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_slider(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield " 
";
        yield from [];
    }

    // line 16
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 17
        yield from $this->loadTemplate("partials/widgets/filter_slide.twig", "home.twig", 17)->unwrap()->yield($context);
        // line 18
        yield from $this->loadTemplate("partials/widgets/membership_plan.twig", "home.twig", 18)->unwrap()->yield($context);
        // line 19
        yield from $this->loadTemplate("partials/widgets/document_filter_tab.twig", "home.twig", 19)->unwrap()->yield($context);
        // line 20
        yield from $this->loadTemplate("partials/widgets/info_section.twig", "home.twig", 20)->unwrap()->yield($context);
        yield from [];
    }

    // line 23
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_jsbottomincludes(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 24
        yield "<!-- REVOLUTION SLIDER SCRIPT -->
<script type=\"text/javascript\" src=\"";
        // line 25
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/themepunch.tools.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 26
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/themepunch.revolution.min.js\"></script>
<script type=\"text/javascript\">
      
\t// var tpj=jQuery;
\t// var revapi4;
\t// tpj(document).ready(function() {
\t// \tif(tpj(\"#rev_slider_4_1\").revolution == undefined){
\t// \t\trevslider_showDoubleJqueryError(\"#rev_slider_4_1\");
\t// \t}else{
\t// \t\trevapi4 = tpj(\"#rev_slider_4_1\").show().revolution({
\t// \t\t\tsliderType:\"standard\",
\t// \t\t\tjsFileLocation:\"scripts/\",
\t// \t\t\tsliderLayout:\"auto\",
\t// \t\t\tdottedOverlay:\"none\",
\t// \t\t\tdelay:9000,
\t// \t\t\tnavigation: {
\t// \t\t\t\tkeyboardNavigation:\"off\",
\t// \t\t\t\tkeyboard_direction: \"horizontal\",
\t// \t\t\t\tmouseScrollNavigation:\"off\",
\t// \t\t\t\tonHoverStop:\"on\",
\t// \t\t\t\ttouch:{
\t// \t\t\t\t\ttouchenabled:\"on\",
\t// \t\t\t\t\tswipe_threshold: 75,
\t// \t\t\t\t\tswipe_min_touches: 1,
\t// \t\t\t\t\tswipe_direction: \"horizontal\",
\t// \t\t\t\t\tdrag_block_vertical: false
\t// \t\t\t\t}
\t// \t\t\t\t,
\t// \t\t\t\tarrows: {
\t// \t\t\t\t\tstyle:\"zeus\",
\t// \t\t\t\t\tenable:true,
\t// \t\t\t\t\thide_onmobile:true,
\t// \t\t\t\t\thide_under:600,
\t// \t\t\t\t\thide_onleave:true,
\t// \t\t\t\t\thide_delay:200,
\t// \t\t\t\t\thide_delay_mobile:1200,
\t// \t\t\t\t\ttmp:'<div class=\"tp-title-wrap\"></div>',
\t// \t\t\t\t\tleft: {
\t// \t\t\t\t\t\th_align:\"left\",
\t// \t\t\t\t\t\tv_align:\"center\",
\t// \t\t\t\t\t\th_offset:40,
\t// \t\t\t\t\t\tv_offset:0
\t// \t\t\t\t\t},
\t// \t\t\t\t\tright: {
\t// \t\t\t\t\t\th_align:\"right\",
\t// \t\t\t\t\t\tv_align:\"center\",
\t// \t\t\t\t\t\th_offset:40,
\t// \t\t\t\t\t\tv_offset:0
\t// \t\t\t\t\t}
\t// \t\t\t\t}
\t// \t\t\t\t,
\t// \t\t\t\tbullets: {
\t// \t\t\tenable:false,
\t// \t\t\thide_onmobile:true,
\t// \t\t\thide_under:600,
\t// \t\t\tstyle:\"hermes\",
\t// \t\t\thide_onleave:true,
\t// \t\t\thide_delay:200,
\t// \t\t\thide_delay_mobile:1200,
\t// \t\t\tdirection:\"horizontal\",
\t// \t\t\th_align:\"center\",
\t// \t\t\tv_align:\"bottom\",
\t// \t\t\th_offset:0,
\t// \t\t\tv_offset:32,
\t// \t\t\tspace:5,
\t// \t\t\ttmp:''
\t// \t\t\t\t}
\t// \t\t\t},
\t// \t\t\tviewPort: {
\t// \t\t\t\tenable:true,
\t// \t\t\t\toutof:\"pause\",
\t// \t\t\t\tvisible_area:\"80%\"
\t// \t\t},
\t// \t\tresponsiveLevels:[1200,992,768,480],
\t// \t\tvisibilityLevels:[1200,992,768,480],
\t// \t\tgridwidth:[1180,1024,778,480],
\t// \t\tgridheight:[440,300,200,100],
\t// \t\tlazyType:\"none\",
\t// \t\tparallax: {
\t// \t\t\ttype:\"mouse\",
\t// \t\t\torigo:\"slidercenter\",
\t// \t\t\tspeed:2000,
\t// \t\t\tlevels:[2,3,4,5,6,7,12,16,10,25,47,48,49,50,51,55],
\t// \t\t\ttype:\"mouse\",
\t// \t\t},
\t// \t\tshadow:0,
\t// \t\tspinner:\"off\",
\t// \t\tstopLoop:\"off\",
\t// \t\tstopAfterLoops:-1,
\t// \t\tstopAtSlide:-1,
\t// \t\tshuffle:\"off\",
\t// \t\tautoHeight:\"off\",
\t// \t\thideThumbsOnMobile:\"off\",
\t// \t\thideSliderAtLimit:0,
\t// \t\thideCaptionAtLimit:0,
\t// \t\thideAllCaptionAtLilmit:0,
\t// \t\tdebugMode:false,
\t// \t\tfallbacks: {
\t// \t\t\tsimplifyAll:\"off\",
\t// \t\t\tnextSlideOnWindowFocus:\"off\",
\t// \t\t\tdisableFocusListener:false,
\t// \t\t}
\t// \t});
\t// \t}
\t// });\t/*ready*/
\t// function initSwiper(swiperClass) {
\tif (window.mySwiper) {
\t\twindow.mySwiper.destroy(true, true);
\t}
\twindow.mySwiper = new Swiper('.swiper', {
\t\t// Optional parameters
\t\tdirection: 'horizontal',
\t\tspeed: 400,
\t\tautoplay: {
\t\t\tdelay: 5000,
\t\t},
\t\tslidesPerView: 4,
\t\tslidesPerGroup: 1,
\t\tspaceBetween: 20,
\t\tbreakpoints: {
\t\t\t// when window width is >= 320px
\t\t\t320: {
\t\t\t\tslidesPerView: 2,
\t\t\t\tspaceBetween: 20
\t\t\t},
\t\t\t// when window width is >= 480px
\t\t\t480: {
\t\t\t\tslidesPerView: 3,
\t\t\t\tspaceBetween: 20
\t\t\t},
\t\t\t// when window width is >= 640px
\t\t\t640: {
\t\t\t\tslidesPerView: 4,
\t\t\t\tspaceBetween: 20
\t\t\t}
\t\t},
\t\tfreeMode: true,
\t\t// loop: true,

\t\t// If we need pagination
\t\tpagination: {
\t\t\tel: '.swiper-pagination',
\t\t\tdynamicBullets: true
\t\t},

\t\t// Navigation arrows
\t\tnavigation: {
\t\t\tnextEl: '.swiper-button-next',
\t\t\tprevEl: '.swiper-button-prev',
\t\t},

\t\t// And if we need scrollbar
\t\tscrollbar: {
\t\t\tel: '.swiper-scrollbar',
\t\t},
\t});
\t// }
\t// initSwiper('.swiper');
\t// initSwiper('.swiper1');
\t// initSwiper('.swiper2');
</script>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "home.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  118 => 26,  114 => 25,  111 => 24,  104 => 23,  99 => 20,  97 => 19,  95 => 18,  93 => 17,  86 => 16,  74 => 13,  61 => 3,  54 => 2,  43 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "home.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\home.twig");
    }
}
