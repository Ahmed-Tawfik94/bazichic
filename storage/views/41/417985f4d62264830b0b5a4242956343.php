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

/* admin/admin_layout.twig */
class __TwigTemplate_468c375f26fe39b8aa142325f88e7621 extends Template
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
<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css\">
";
        // line 16
        yield "    <link href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/css/plugins/datedropper.css\" rel=\"stylesheet\" type=\"text/css\">
    <script src=\"https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js\"></script>
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css\" integrity=\"sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N\" crossorigin=\"anonymous\">
    <link href=\"https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css\" rel=\"stylesheet\">
    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js\"></script>


    <script type=\"text/javascript\">
        toastr.options = {
            \"closeButton\": true,
            \"debug\": false,
            \"newestOnTop\": true,
            \"progressBar\": true,
            \"positionClass\": \"toast-top-right\",
            \"preventDuplicates\": true,
            \"onclick\": null,
            \"showDuration\": \"300\",
            \"hideDuration\": \"1000\",
            \"timeOut\": \"5000\",
            \"extendedTimeOut\": \"1000\",
            \"showEasing\": \"swing\",
            \"hideEasing\": \"linear\",
            \"showMethod\": \"fadeIn\",
            \"hideMethod\": \"fadeOut\"
        }
        var authorization = '";
        // line 41
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "api_key", [], "any", false, false, false, 41), "html", null, true);
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
    <style>
        body{
            background: #f7f7f7;
        }
    </style>
    ";
        // line 87
        yield from $this->unwrap()->yieldBlock('jstriggers', $context, $blocks);
        // line 88
        yield "    <script type=\"text/javascript\" src=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/front_script.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 89
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/script.js\"></script>

</head>
<body>
<div id=\"wrapper\">
    <header id=\"header-container\" class=\"fixed fullwidth dashboard\">
        ";
        // line 95
        yield from $this->loadTemplate("admin/partials/top_header.twig", "admin/admin_layout.twig", 95)->unwrap()->yield($context);
        // line 96
        yield "    </header>
</div>
<!-- Header / End -->
<div class=\"clearfix\"></div>
<!-- Header Container / End -->

<!-- Dashboard -->
<div id=\"dashboard\">

    ";
        // line 105
        yield from $this->loadTemplate("admin/partials/dash_nav.twig", "admin/admin_layout.twig", 105)->unwrap()->yield($context);
        // line 106
        yield "    <!-- Content
        ================================================== -->
    <div class=\"dashboard-content\">
        ";
        // line 109
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 110
        yield "    </div>

</div>
<!-- END OF DASHBOARD -->
<!-- Wrapper / End -->


<!-- Scripts
================================================== -->

<script type=\"text/javascript\" src=\"";
        // line 120
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/mmenu.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 121
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/chosen.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 122
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/slick.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 123
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/rangeslider.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 124
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/magnific-popup.min.js\"></script>
<script src=\"";
        // line 125
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/bazisearch/extention/flatpickr.js\"></script>

";
        // line 129
        yield "
";
        // line 132
        yield "<script type=\"text/javascript\" src=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/jquery-ui.min.js\"></script>
<script type=\"text/javascript\" src=\"";
        // line 133
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/tooltips.min.js\"></script>
";
        // line 135
        yield "<script src=\"https://unpkg.com/counterup2@2.0.2/dist/index.js\">\t</script>
<script type=\"text/javascript\" src=\"";
        // line 136
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/custom.js\"></script>
<script src=\"";
        // line 137
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/sweetalert2.all.min.js\"></script>
<script src=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js\"
        integrity=\"sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct\"
        crossorigin=\"anonymous\"></script>

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
        disableMobile: true,    // Always use Flatpickr's calendar UI
});
});
</script>
<script type=\"text/javascript\" src=\"";
        // line 158
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/scripts/src/referrals.js\"></script>
";
        // line 159
        yield from $this->unwrap()->yieldBlock('jsfooter', $context, $blocks);
        // line 160
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

    // line 87
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_jstriggers(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 109
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 159
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
        return "admin/admin_layout.twig";
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
        return array (  324 => 159,  314 => 109,  304 => 87,  294 => 6,  287 => 160,  285 => 159,  281 => 158,  257 => 137,  253 => 136,  250 => 135,  246 => 133,  241 => 132,  238 => 129,  233 => 125,  229 => 124,  225 => 123,  221 => 122,  217 => 121,  213 => 120,  201 => 110,  199 => 109,  194 => 106,  192 => 105,  181 => 96,  179 => 95,  170 => 89,  165 => 88,  163 => 87,  114 => 41,  85 => 16,  80 => 13,  76 => 12,  72 => 11,  68 => 10,  64 => 9,  58 => 7,  56 => 6,  52 => 5,  46 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "admin/admin_layout.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\admin\\admin_layout.twig");
    }
}
