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

/* partials/nav.twig */
class __TwigTemplate_fea16ac442033768577050d3ce65429f extends Template
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
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<div class=\"navbar navbar-expand-md navbar-light position-relative \">
    <a class=\"navbar-brand\" href=\"";
        // line 2
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("home"), "html", null, true);
        yield "\">
            <img src=\"/images/logo3.png\" alt=\"BaziChic Chinese Metatphysics Consultancy\" width=\"64\" height=\"64\"/>
\t</a>
        <button class=\"navbar-toggler border-0\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarSupportedContent\"
                aria-controls=\"navbarSupportedContent\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>
        <div class=\"collapse navbar-collapse\" id=\"navbarSupportedContent\">
            <ul class=\"navbar-nav mr-auto\">
                <li class=\"nav-item active\">
                    <a class=\"nav-link\" href=\"";
        // line 12
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("home"), "html", null, true);
        yield "\">Home <span class=\"sr-only\">(current)</span></a>
                </li>

                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"";
        // line 16
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("e-book-store"), "html", null, true);
        yield "\">Browse</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"";
        // line 19
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("about"), "html", null, true);
        yield "\">About Us</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"";
        // line 22
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("contact"), "html", null, true);
        yield "\">Contact Us</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"";
        // line 25
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("calendar"), "html", null, true);
        yield "\">e-TongShu</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"";
        // line 28
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("frequently-asked-questions"), "html", null, true);
        yield "\">Help Center</a>
                </li>

            </ul>
            ";
        // line 32
        if (((CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "first_name", [], "any", false, false, false, 32) && CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "last_name", [], "any", false, false, false, 32)) && CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "email", [], "any", false, false, false, 32))) {
            // line 33
            yield "            <div class=\"dropdown px-0 px-md-4\" style=\"user-select: none;\">
                <a class=\"dropdown-toggle text-decoration-none text-dark \" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"false\">
                    ";
            // line 35
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "user_image", [], "any", false, false, false, 35)) {
                // line 36
                yield "                        <img src=\"/";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "user_image", [], "any", false, false, false, 36), "html", null, true);
                yield "\" alt=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "first_name", [], "any", false, false, false, 36), "html", null, true);
                yield "\" class=\"rounded-full\" width=\"40\" height=\"40\">
                    ";
            } else {
                // line 38
                yield "                        <img src=\"/images/avatar.jpg\" alt=\"placeholder\" class=\"rounded-full\"  width=\"40\" height=\"40\">
                    ";
            }
            // line 40
            yield "                    ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "first_name", [], "any", false, false, false, 40), "html", null, true);
            yield "
                </a>
                <div class=\"dropdown-menu right-0\">
                    <a class=\"dropdown-item\" href=\"";
            // line 43
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("dashboard"), "html", null, true);
            yield "\"><i class=\"fa fa-line-chart mr-2\"></i>Dashboard</a>
                    <a class=\"dropdown-item\" href=\"";
            // line 44
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("logout"), "html", null, true);
            yield "\"><i class=\"sl sl-icon-power mr-2\"></i>Logout</a>
                </div>
            </div>
            ";
        } else {
            // line 48
            yield "                <a href=\"#sign-in-dialog\" class=\"sign-in popup-with-zoom-anim text-decoration-none btn-link text-dark\"  style=\"user-select: none;\">
                    <i class=\"sl sl-icon-login\"></i>
                    Login</a>
            ";
        }
        // line 52
        yield "        </div>

        <!-- Sign In Popup -->
        <div id=\"sign-in-dialog\" class=\"zoom-anim-dialog mfp-hide\">
            <div class=\"small-dialog-header\">
                <h3>Sign In</h3>
            </div>
            <div class=\"sign-in-form style-1\">
                ";
        // line 60
        yield from $this->loadTemplate("forms/login_form.twig", "partials/nav.twig", 60)->unwrap()->yield($context);
        // line 61
        yield "            </div>
        </div>
        <!-- Sign In Popup / End -->
</div>
";
        // line 66
        yield "    <div class=\"col-lg-12 col-md-12 col-sm-12 d-none d-md-block \" >
        ";
        // line 67
        yield from $this->loadTemplate("forms/noboot-search-form.twig", "partials/nav.twig", 67)->unwrap()->yield($context);
        // line 68
        yield "    </div>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "partials/nav.twig";
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
        return array (  163 => 68,  161 => 67,  158 => 66,  152 => 61,  150 => 60,  140 => 52,  134 => 48,  127 => 44,  123 => 43,  116 => 40,  112 => 38,  104 => 36,  102 => 35,  98 => 33,  96 => 32,  89 => 28,  83 => 25,  77 => 22,  71 => 19,  65 => 16,  58 => 12,  45 => 2,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "partials/nav.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\partials\\nav.twig");
    }
}
