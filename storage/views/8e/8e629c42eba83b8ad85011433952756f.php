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

/* dashboard_layout.twig */
class __TwigTemplate_0d3550a247097dcc92403366b1de9fa9 extends Template
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
            'title' => [$this, 'block_title'],
            'jstriggers' => [$this, 'block_jstriggers'],
            'content' => [$this, 'block_content'],
            'jsfooter' => [$this, 'block_jsfooter'],
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
    ";
        // line 6
        yield from $this->unwrap()->yieldBlock('title', $context, $blocks);
        // line 7
        yield "    <meta name=\"description\" content=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "description", [], "any", false, false, false, 7), "html", null, true);
        yield "\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <link rel=\"shortcut icon\" type=\"image/png\" href=\"";
        // line 9
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/images/favicon.png\"/>
    <link rel=\"stylesheet\" href=\"";
        // line 10
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/invoice.css\">
    <link rel=\"stylesheet\" href=\"";
        // line 11
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/style.css\">
    <link rel=\"stylesheet\" href=\"";
        // line 12
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/custom.css\">
    <link rel=\"stylesheet\" href=\"";
        // line 13
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/colors/main.css\" id=\"colors\">
    <link
            rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css\">

    ";
        // line 18
        yield "    <link href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/plugins/datedropper.css\" rel=\"stylesheet\" type=\"text/css\">
    <script src=\"https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js\"></script>
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css\"
          integrity=\"sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N\" crossorigin=\"anonymous\">
    <link href=\"https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css\" rel=\"stylesheet\">
    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js\"></script>
    <script type=\"text/javascript\">
        var authorization = '";
        // line 25
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "api_key", [], "any", false, false, false, 25), "html", null, true);
        yield "';

        function docImgPreview() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById(\"cover_image\").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById(\"cover_image_preview\").src = oFREvent.target.result;
                \$('#docMediaUploadForm').submit();
            };
        }

        function profileImgPreview() {

            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById(\"profile_image\").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById(\"profilePreview\").src = oFREvent.target.result;
// \$('#servicePhotoForm').submit();
            };
        }

        function showPdfPreview() { // alert(\"Preview loaded\");
            parseDoc();
            pdffile = document.getElementById(\"doc_link\").files[0];
            pdffile_url = URL.createObjectURL(pdffile);
            \$('#viewer').attr('src', pdffile_url);
            \$('#docFileUploadForm').submit();
        }

        function parseDoc() {
            var input = document.getElementById(\"doc_link\");
            var reader = new FileReader();
            reader.readAsBinaryString(input.files[0]);
            reader.onloadend = function () {
                var count = reader.result.match(/\\/Type[\\s]*\\/Page[^s]/g).length;
// console.log('Number of Pages:',count );
// alert(\"Preview loaded => \"+count);
                \$('#num_pages').attr('value', count);
            }
        }
    </script>
    ";
        // line 66
        yield from $this->unwrap()->yieldBlock('jstriggers', $context, $blocks);
        // line 67
        yield "    <script type=\"text/javascript\" src=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/front_script.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 68
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/script.js\"></script>
    <style>
        body {
            background: #F7F7F7 !important;
        }
    </style>
    <style>
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #fff;
            border-top: 1px solid #ddd;
            z-index: 10000;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }
        .nav-item {
            flex: 1;
            text-align: center;
        }
        .nav-link {
            padding: 10px;
            font-size: 14px;
            color: #555;
        }
        .nav-link.active {
            color: #007bff;
        }
    </style>
</head>
<body>
<div id=\"wrapper\">
    <header id=\"header-container\" class=\"fixed fullwidth dashboard\">
        ";
        // line 101
        yield from $this->loadTemplate("partials/public_top_navbar.twig", "dashboard_layout.twig", 101)->unwrap()->yield($context);
        // line 102
        yield "    </header>
</div>
<!-- Header / End -->
<div class=\"clearfix\"></div>
<!-- Header Container / End -->

<!-- Dashboard -->
<div id=\"dashboard\">
    ";
        // line 110
        yield from $this->loadTemplate("partials/user_dashboard.twig", "dashboard_layout.twig", 110)->unwrap()->yield($context);
        // line 111
        yield "    <!-- Content
            ================================================== -->
    <div class=\"dashboard-content\"> ";
        // line 113
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 114
        yield "    </div>


    <nav class=\"navbar navbar-dark bg-dark text-white bottom-nav d-block d-md-none px-0 py-2\">
        <div class=\"container d-flex\">
            <a class=\"nav-item nav-link text-white ";
        // line 119
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 119) == "dashboard")) {
            yield " active ";
        }
        yield "\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("dashboard"), "html", null, true);
        yield "\">
                <i class=\"sl sl-icon-rocket\" style=\"font-size: 20px\"></i>
            </a>
            <a class=\"nav-item nav-link text-white ";
        // line 122
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 122) == "notification")) {
            yield " active ";
        }
        yield "\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("my_notifications"), "html", null, true);
        yield "\">
                <i class=\"bi bi-bell\" style=\"font-size: 20px\"></i>
            </a>
            <a class=\"nav-item nav-link text-white ";
        // line 125
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 125) == "referrals")) {
            yield " active ";
        }
        yield "\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("referral-codes"), "html", null, true);
        yield "\">
                <i class=\"sl sl-icon-rocket\" style=\"font-size: 20px\"></i>
            </a>
            <a class=\"nav-item nav-link text-white position-relative ";
        // line 128
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 128) == "savedreads")) {
            yield " active ";
        }
        yield "\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("saved-reads"), "html", null, true);
        yield "\">
                <i class=\"sl im im-icon-Arrow-LeftinCircle\" style=\"font-size: 20px\"></i>
                ";
        // line 130
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "num_saves", [], "any", false, false, false, 130)) {
            // line 131
            yield "                    <span class=\"badge badge-danger rounded-circle position-absolute \"
                    style=\"top:0\"
                    >";
            // line 133
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "num_saves", [], "any", false, false, false, 133), "html", null, true);
            yield "</span>
                ";
        }
        // line 135
        yield "            </a>
            <a class=\"nav-item nav-link text-white ";
        // line 136
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 136) == "favourites")) {
            yield " active ";
        }
        yield "\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("bookmarks"), "html", null, true);
        yield "\">
                <i class=\"sl sl-icon-heart\" style=\"font-size: 20px\"></i>
            </a>
            <a class=\"nav-item nav-link text-white ";
        // line 139
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 139) == "profile")) {
            yield " active ";
        }
        yield "\"  href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("my-profile"), "html", null, true);
        yield "\">
                <i class=\"sl sl-icon-user\" style=\"font-size: 20px\"></i>
            </a>
            <a class=\"nav-item nav-link text-white ";
        // line 142
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 142) == "my-membership")) {
            yield " active ";
        }
        yield "\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("my-subscriptions"), "html", null, true);
        yield "\">
                <i class=\"sl sl-icon-shield\" style=\"font-size: 20px\"></i>
            </a>
            <a class=\"nav-item nav-link text-white\" href=\"";
        // line 145
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("logout"), "html", null, true);
        yield "\">
                <i class=\"sl sl-icon-power\" style=\"font-size: 20px\"></i>
            </a>
        </div>
    </nav>

</div>
<!-- END OF DASHBOARD -->
<!-- Wrapper / End -->


<!-- Scripts
================================================== -->
<script type=\"text/javascript\" src=\"";
        // line 158
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/mmenu.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 159
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/chosen.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 160
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/slick.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 161
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/rangeslider.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 162
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/magnific-popup.min.js\"></script>
<script src=\"";
        // line 163
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/bazisearch/extention/flatpickr.js\"></script>

";
        // line 167
        yield "
";
        // line 170
        yield "<script type=\"text/javascript\" src=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/jquery-ui.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 171
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/tooltips.min.js\"></script>
";
        // line 173
        yield "<script src=\"https://unpkg.com/counterup2@2.0.2/dist/index.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 174
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/custom.js\"></script>
<script src=\"";
        // line 175
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/sweetalert2.all.min.js\"></script>
<script src=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js\"
        integrity=\"sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct\"
        crossorigin=\"anonymous\"></script>

<script
        src=\"https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js\"
        integrity=\"sha512-4F1cxYdMiAW98oomSLaygEwmCnIP38pb4Kx70yQYqRwLVCs3DbRumfBq82T08g/4LJ/smbFGFpmeFlQgoDccgg==\"
        crossorigin=\"anonymous\"
        referrerpolicy=\"no-referrer\"
></script>
<script>

    document.addEventListener(\"DOMContentLoaded\", function () {
        flatpickr(\"#dob\", {
            dateFormat: \"d-m-Y\", // Date format: dd-mm-yyyy
            minDate: \"01-01-1901\", // Minimum date
            maxDate: \"31-12-2019\", // Maximum date
            defaultDate: null, // No preselected date
            disableMobile: false, // Ensures mobile-friendly UI
            yearRange: [
                1901, 2019
            ], // Limits year dropdown range
            disableMobile: true, // Always use Flatpickr's calendar UI
        });
    });

</script>
<script type=\"text/javascript\" src=\"";
        // line 203
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/src/referrals.js\"></script>
";
        // line 204
        yield from $this->unwrap()->yieldBlock('jsfooter', $context, $blocks);
        // line 205
        yield "</body>
</html>
";
        yield from [];
    }

    // line 6
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 66
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_jstriggers(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 113
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 204
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_jsfooter(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "dashboard_layout.twig";
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
        return array (  429 => 204,  419 => 113,  409 => 66,  399 => 6,  392 => 205,  390 => 204,  386 => 203,  355 => 175,  351 => 174,  348 => 173,  344 => 171,  339 => 170,  336 => 167,  331 => 163,  327 => 162,  323 => 161,  319 => 160,  315 => 159,  311 => 158,  295 => 145,  285 => 142,  275 => 139,  265 => 136,  262 => 135,  257 => 133,  253 => 131,  251 => 130,  242 => 128,  232 => 125,  222 => 122,  212 => 119,  205 => 114,  203 => 113,  199 => 111,  197 => 110,  187 => 102,  185 => 101,  149 => 68,  144 => 67,  142 => 66,  98 => 25,  87 => 18,  80 => 13,  76 => 12,  72 => 11,  68 => 10,  64 => 9,  58 => 7,  56 => 6,  52 => 5,  46 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "dashboard_layout.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\dashboard_layout.twig");
    }
}
