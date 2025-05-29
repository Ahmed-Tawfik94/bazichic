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

/* 404.twig */
class __TwigTemplate_5e18222240c9eb2c250ea7b1dfbdc03b extends Template
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
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 2
        return $this->loadTemplate(($context["layout_template"] ?? null), "404.twig", 2);
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        $context["layout_template"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "userID", [], "any", false, false, false, 1) == 1)) ? ("admin/admin_layout.twig") : ("layout.twig"));
        // line 2
        yield from $this->getParent($context)->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 4
        yield "<div class=\"container\">
\t<div class=\"row\">
\t\t<div class=\"col-md-12\">

\t\t\t<section id=\"not-found\" class=\"center\">
\t\t\t\t<h2><i class=\"fa fa-question-circle\"></i></h2>
\t\t\t\t<p>We're sorry, but the page you were looking for doesn't exist.</p>

\t\t\t\t<!-- Search -->
\t\t\t\t<div class=\"row\">
\t\t\t\t\t<div class=\"col-lg-8 col-lg-offset-2\">
\t\t\t\t\t\t<a href=\"";
        // line 15
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "\" class=\"button btn btn-purple text-white\">Go to Home page</a>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</section>

\t\t</div>
\t</div>

</div>
<!-- Container / End -->
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "404.twig";
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
        return array (  73 => 15,  60 => 4,  53 => 3,  49 => 2,  47 => 1,  40 => 2,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "404.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\404.twig");
    }
}
