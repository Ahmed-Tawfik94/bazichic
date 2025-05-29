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

/* admin/partials/top_header.twig */
class __TwigTemplate_542463a4e72aecaca56568c5b460e1b4 extends Template
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
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("admin-panel"), "html", null, true);
        yield "\">
\t\t<img src=\"/images/logo3.png\" alt=\"BaziChic Chinese Metatphysics Consultancy\" width=\"64\" height=\"64\"/>
\t</a>
\t<button class=\"navbar-toggler border-0\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarSupportedContent\"
\t\t\taria-controls=\"navbarSupportedContent\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
\t\t<span class=\"navbar-toggler-icon\"></span>
\t</button>
\t<div class=\"collapse navbar-collapse justify-content-end\" id=\"navbarSupportedContent\">
";
        // line 14
        yield "
";
        // line 30
        yield "
";
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
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("admin-panel"), "html", null, true);
        yield "\"><i class=\"fa fa-line-chart mr-2\"></i>Dashboard</a>
\t\t\t\t\t<a class=\"dropdown-item\" href=\"";
        // line 44
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("notifications"), "html", null, true);
        yield "\"><i class=\"fa fa-bell mr-2\"></i>Notifications</a>
\t\t\t\t\t<a class=\"dropdown-item\" href=\"";
        // line 45
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("logout"), "html", null, true);
        yield "\"><i class=\"sl sl-icon-power mr-2\"></i>Logout</a>
\t\t\t\t</div>
\t\t\t</div>
";
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
        yield from $this->loadTemplate("forms/login_form.twig", "admin/partials/top_header.twig", 61)->unwrap()->yield($context);
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
        return "admin/partials/top_header.twig";
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
        return array (  113 => 62,  111 => 61,  101 => 53,  95 => 45,  91 => 44,  87 => 43,  80 => 40,  76 => 38,  68 => 36,  66 => 35,  62 => 33,  59 => 30,  56 => 14,  45 => 2,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "admin/partials/top_header.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\admin\\partials\\top_header.twig");
    }
}
