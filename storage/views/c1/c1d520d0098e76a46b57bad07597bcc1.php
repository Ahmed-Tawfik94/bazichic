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

/* partials/public_top_navbar.twig */
class __TwigTemplate_9537279be5506124b04f2c0afe32a756 extends Template
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
\t<a class=\"navbar-brand\" href=\"";
        // line 2
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("home"), "html", null, true);
        yield "\">
\t\t<img src=\"/images/logo3.png\" alt=\"BaziChic Chinese Metatphysics Consultancy\" width=\"64\" height=\"64\"/>
\t</a>
\t<button class=\"navbar-toggler border-0\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarSupportedContent\"
\t\t\taria-controls=\"navbarSupportedContent\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
\t\t<span class=\"navbar-toggler-icon\"></span>
\t</button>
\t<div class=\"collapse navbar-collapse\" id=\"navbarSupportedContent\">
\t\t<ul class=\"navbar-nav mr-auto\" style=\"width: 600px;\">
\t\t\t<li class=\"nav-item active\">
\t\t\t\t<a class=\"nav-link\" href=\"";
        // line 12
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("home"), "html", null, true);
        yield "\">Home <span class=\"sr-only\">(current)</span></a>
\t\t\t</li>

\t\t\t<li class=\"nav-item\">
\t\t\t\t<a class=\"nav-link\" href=\"";
        // line 16
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("e-book-store"), "html", null, true);
        yield "\">Browse</a>
\t\t\t</li>
\t\t\t<li class=\"nav-item\">
\t\t\t\t<a class=\"nav-link\" href=\"";
        // line 19
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("about"), "html", null, true);
        yield "\">About Us</a>
\t\t\t</li>
\t\t\t<li class=\"nav-item\">
\t\t\t\t<a class=\"nav-link\" href=\"";
        // line 22
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("contact"), "html", null, true);
        yield "\">Contact Us</a>
\t\t\t</li>
\t\t\t<li class=\"nav-item\">
\t\t\t\t<a class=\"nav-link\" href=\"";
        // line 25
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("calendar"), "html", null, true);
        yield "\">e-TongShu</a>
\t\t\t</li>
\t\t\t<li class=\"nav-item\">
\t\t\t\t<a class=\"nav-link\" href=\"";
        // line 28
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("frequently-asked-questions"), "html", null, true);
        yield "\">Help Center</a>
\t\t\t</li>

\t\t</ul>
\t\t";
        // line 32
        if (((CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "first_name", [], "any", false, false, false, 32) && CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "last_name", [], "any", false, false, false, 32)) && CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "email", [], "any", false, false, false, 32))) {
            // line 33
            yield "\t\t\t<div class=\"dropdown px-0 px-md-4\" style=\"user-select: none;\">
\t\t\t\t<a class=\"dropdown-toggle text-decoration-none text-dark \" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"false\">
\t\t\t\t\t";
            // line 35
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "user_image", [], "any", false, false, false, 35)) {
                // line 36
                yield "\t\t\t\t\t\t<img src=\"/";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "user_image", [], "any", false, false, false, 36), "html", null, true);
                yield "\" alt=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "first_name", [], "any", false, false, false, 36), "html", null, true);
                yield "\" width=\"40\" height=\"40\">
\t\t\t\t\t";
            } else {
                // line 38
                yield "\t\t\t\t\t\t<img src=\"/images/avatar.jpg\" alt=\"placeholder\"  width=\"40\" height=\"40\">
\t\t\t\t\t";
            }
            // line 40
            yield "\t\t\t\t\t";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "first_name", [], "any", false, false, false, 40), "html", null, true);
            yield "
\t\t\t\t</a>
\t\t\t\t<div class=\"dropdown-menu right-0\">
\t\t\t\t\t<a class=\"dropdown-item\" href=\"";
            // line 43
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("dashboard"), "html", null, true);
            yield "\"><i class=\"fa fa-line-chart mr-2\"></i>Dashboard</a>
";
            // line 45
            yield "\t\t\t\t\t<a class=\"dropdown-item\" href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("logout"), "html", null, true);
            yield "\"><i class=\"sl sl-icon-power mr-2\"></i>Logout</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t";
        } else {
            // line 49
            yield "\t\t\t<a href=\"#sign-in-dialog\" class=\"sign-in popup-with-zoom-anim text-decoration-none btn-link text-dark\"  style=\"user-select: none;\">
\t\t\t\t<i class=\"sl sl-icon-login\"></i>
\t\t\t\tLogin</a>
\t\t";
        }
        // line 53
        yield "\t</div>

\t<!-- Sign In Popup -->
\t<div id=\"sign-in-dialog\" class=\"zoom-anim-dialog mfp-hide\">
\t\t<div class=\"small-dialog-header\">
\t\t\t<h3>Sign In</h3>
\t\t</div>
\t\t<div class=\"sign-in-form style-1\">
\t\t\t";
        // line 61
        yield from $this->loadTemplate("forms/login_form.twig", "partials/public_top_navbar.twig", 61)->unwrap()->yield($context);
        // line 62
        yield "\t\t</div>
\t</div>
\t<!-- Sign In Popup / End -->
</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "partials/public_top_navbar.twig";
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
        return array (  153 => 62,  151 => 61,  141 => 53,  135 => 49,  127 => 45,  123 => 43,  116 => 40,  112 => 38,  104 => 36,  102 => 35,  98 => 33,  96 => 32,  89 => 28,  83 => 25,  77 => 22,  71 => 19,  65 => 16,  58 => 12,  45 => 2,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "partials/public_top_navbar.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\partials\\public_top_navbar.twig");
    }
}
