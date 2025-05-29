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

/* layout.twig */
class __TwigTemplate_d4103a6e0b40696e62455e189afd2ee4 extends Template
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

        $this->parent = false;

        $this->blocks = [
            'jstriggers' => [$this, 'block_jstriggers'],
            'slider' => [$this, 'block_slider'],
            'content' => [$this, 'block_content'],
            'jsbottomincludes' => [$this, 'block_jsbottomincludes'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
    <title>";
        // line 5
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "title", [], "any", false, false, false, 5), "html", null, true);
        yield "</title>
    <meta name=\"description\" content=\"";
        // line 6
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "description", [], "any", false, false, false, 6), "html", null, true);
        yield "\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    ";
        // line 8
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "og_url", [], "any", false, false, false, 8)) {
            // line 9
            yield "        <meta property=\"og:url\" content=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "og_url", [], "any", false, false, false, 9), "html", null, true);
            yield "\"/>
    ";
        } else {
            // line 11
            yield "        <meta property=\"og:url\" content=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            yield "\"/>
    ";
        }
        // line 13
        yield "    <meta property=\"og:type\" content=\"article\"/>
    ";
        // line 14
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "og_title", [], "any", false, false, false, 14)) {
            // line 15
            yield "        <meta property=\"og:title\" content=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "og_title", [], "any", false, false, false, 15), "html", null, true);
            yield "\"/>
    ";
        } else {
            // line 17
            yield "        <meta property=\"og:title\" content=\"BaziChic Chinese Metaphysics Consultancy\"/>
    ";
        }
        // line 19
        yield "
    <meta property=\"og:description\"
          content=\"Discover Unlimited Chinese Metaphysics E-Books, Magazines and eTongshu on BaziChic.\"/>
    ";
        // line 22
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "og_image", [], "any", false, false, false, 22)) {
            // line 23
            yield "        <meta property=\"og:image\" content=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "og_image", [], "any", false, false, false, 23), "html", null, true);
            yield "\"/>
    ";
        } else {
            // line 25
            yield "        <meta property=\"og:image\" content=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            yield "/images/banners/main_banner.png\"/>
    ";
        }
        // line 27
        yield "
    ";
        // line 28
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "secure_img_url", [], "any", false, false, false, 28)) {
            // line 29
            yield "        <meta property=\"og:image:secure_url\" content=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "secure_img_url", [], "any", false, false, false, 29), "html", null, true);
            yield "\"/>
    ";
        } else {
            // line 31
            yield "        <meta property=\"og:image:secure_url\" content=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            yield "/images/logo3.png\"/>
    ";
        }
        // line 33
        yield "
    <link rel=\"shortcut icon\" type=\"image/png\" href=\"";
        // line 34
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/images/favicon.png\"/>
    <link rel=\"stylesheet\" href=\"";
        // line 35
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/style.css\">

    <link rel=\"stylesheet\" href=\"";
        // line 37
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/colors/main.css\" id=\"colors\">
    <link rel=\"stylesheet\" href=\"";
        // line 38
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/shop.css\">
    <link rel=\"stylesheet\" href=\"";
        // line 39
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/bootstrap-search-menu.css\">
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css\"
          integrity=\"sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N\" crossorigin=\"anonymous\">
    <script src=\"https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 43
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/front_script.js\"></script>
    <link href=\"https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css\" rel=\"stylesheet\">
    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js\"></script>
    <link
            rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css\">
    <!-- Flatpickr CSS -->
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css\">

    <link rel=\"stylesheet\" href=\"";
        // line 51
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/pricing-cards.css\">
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css\"/>
    ";
        // line 53
        yield from $this->unwrap()->yieldBlock('jstriggers', $context, $blocks);
        // line 54
        yield "    <script>
        \$(function () {
            \$(\".input-group-btn .dropdown-menu li a\").click(function () {
                var selText = \$(this).html();
                \$(this).parents('.input-group-btn').find('.btn-search').html(selText);

            });

        });
    </script>
    <script type=\"text/javascript\"
            src=\"https://platform-api.sharethis.com/js/sharethis.js#property=5e69dcf284c12600134a414d&product=inline-share-buttons\"
            async=\"async\"></script>
</head>
<body>
<!-- Wrapper -->
<div
        id=\"wrapper\">

    <!-- Header Container
                                ================================================== -->
    <header
            id=\"header-container\">
        <!-- Header -->
        <div id=\"header\">
            <div class=\"container-fluid\" style=\"\">
                ";
        // line 80
        yield from $this->loadTemplate("partials/nav.twig", "layout.twig", 80)->unwrap()->yield($context);
        // line 81
        yield "            </div>
            <!-- Left Side Content / End -->
        </div>
    </header>
</div>
<!-- Header / End -->
<div class=\"clearfix\"></div>
<!-- Header Container / End -->
";
        // line 89
        yield from $this->unwrap()->yieldBlock('slider', $context, $blocks);
        // line 90
        yield "<!-- Container -->
";
        // line 91
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 92
        yield "<!-- Container / End -->
";
        // line 93
        yield from $this->loadTemplate("partials/footer.twig", "layout.twig", 93)->unwrap()->yield($context);
        // line 94
        yield "
<!-- Back To Top Button -->
<div id=\"backtotop\">
    <a href=\"#\"></a>
</div>


<script type=\"text/javascript\" src=\"";
        // line 101
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/mmenu.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 102
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/chosen.jquery.min.js\"></script>

<script type=\"text/javascript\" src=\"";
        // line 104
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/chosen.proto.min.js\"></script>
";
        // line 106
        yield "
<script type=\"text/javascript\" src=\"";
        // line 107
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/slick.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 108
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/rangeslider.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 109
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/magnific-popup.min.js\"></script>
";
        // line 112
        yield "<script src=\"https://unpkg.com/counterup2@2.0.2/dist/index.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 113
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/jquery-ui.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 114
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/tooltips.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 115
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/custom.js\"></script>

<!-- Masonry Filtering -->
<script type=\"text/javascript\" src=\"";
        // line 118
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/isotope.min.js\"></script>
<script>
    \$(document).ready(function () {
        \$(\"#searchdropdown li a\").click(function () {
            \$(\"#searchcategory\").html(\$(this).text() + ' <span class=\"caret\"></span>');
            \$(\"#searchcategory\").val(\$(this).text());
        });
    });
</script>
<!-- SLIDER REVOLUTION 5.0 EXTENSIONS
                (Load Extensions only on Local File Systems !
                The following part can be removed on Server for On Demand Loading) -->

<script src=\"";
        // line 131
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/bazisearch/extention/choices.js\"></script>
<script src=\"";
        // line 132
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/bazisearch/extention/flatpickr.js\"></script>


<script>
    const choices = new Choices('[data-trigger]', {
        searchEnabled: false,
        itemSelectText: ''
    });

    document.addEventListener(\"DOMContentLoaded\", function () {
        flatpickr(\"#dob\", {
            dateFormat: \"d-m-Y\", // Date format: dd-mm-yyyy
            minDate: \"01-01-1901\", // Minimum date
            maxDate: \"31-12-2019\", // Maximum date
            defaultDate: null, // No preselected date
            yearRange: [
                1901, 2019
            ], // Limits year dropdown range
            disableMobile: true,    // Always use Flatpickr's calendar UI
        });
    });
</script>

";
        // line 157
        yield "<!-- Date Picker - docs: http://www.vasterad.com/docs/listeo/#!/date_picker -->
<!-- Time Picker - docs: http://www.vasterad.com/docs/listeo/#!/time_picker -->
";
        // line 160
        yield "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/timedropper/1.0/timedropper.min.js\"
        integrity=\"sha512-f4mnC8WQRS2wA6YylLpyD99m/aw7/DZq6N4qQ12cu0wQQXWwrDU4afX3YUn/oGqDcgT1vkRu1XcidsV3LfqNNw==\"
        crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 163
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/plugins/timedropper.css\">
<script src=\"/scripts/sweetalert2.all.min.js\"></script>
<script></script>
";
        // line 167
        yield "<script src=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js\"
        integrity=\"sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct\"
        crossorigin=\"anonymous\"></script>
<!-- Bootstrap Datepicker JS -->

";
        // line 173
        yield "<script src=\"https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js\"></script>
";
        // line 174
        yield from $this->unwrap()->yieldBlock('jsbottomincludes', $context, $blocks);
        // line 175
        yield "</body>
</html>
";
        yield from [];
    }

    // line 53
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_jstriggers(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 89
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_slider(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 91
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 174
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_jsbottomincludes(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "layout.twig";
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
        return array (  387 => 174,  377 => 91,  367 => 89,  357 => 53,  350 => 175,  348 => 174,  345 => 173,  338 => 167,  332 => 163,  327 => 160,  323 => 157,  297 => 132,  293 => 131,  277 => 118,  271 => 115,  267 => 114,  263 => 113,  260 => 112,  256 => 109,  252 => 108,  248 => 107,  245 => 106,  241 => 104,  236 => 102,  232 => 101,  223 => 94,  221 => 93,  218 => 92,  216 => 91,  213 => 90,  211 => 89,  201 => 81,  199 => 80,  171 => 54,  169 => 53,  164 => 51,  153 => 43,  146 => 39,  142 => 38,  138 => 37,  133 => 35,  129 => 34,  126 => 33,  120 => 31,  114 => 29,  112 => 28,  109 => 27,  103 => 25,  97 => 23,  95 => 22,  90 => 19,  86 => 17,  80 => 15,  78 => 14,  75 => 13,  69 => 11,  63 => 9,  61 => 8,  56 => 6,  52 => 5,  46 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "layout.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\layout.twig");
    }
}
