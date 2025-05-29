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

/* partials/widgets/membership-table.twig */
class __TwigTemplate_30949c275063a7d490124eedac015c32 extends Template
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
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "membership_plans", [], "any", false, false, false, 1)) > 0)) {
            // line 2
            yield "    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "membership_plans", [], "any", false, false, false, 2));
            foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                // line 3
                yield "        <div class=\"col-12 col-md-";
                if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "membership_plans", [], "any", false, false, false, 3)) == 2)) {
                    yield "6";
                }
                if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "membership_plans", [], "any", false, false, false, 3)) == 3)) {
                    yield "4";
                }
                yield " wow fadeInUp\" data-wow-duration=\"1s\" data-wow-delay=\"0.1s\" data-wow-offset=\"0\">
            <div class=\"single-pricing  ";
                // line 4
                if (CoreExtension::getAttribute($this->env, $this->source, $context["row"], "is_highlighted", [], "any", false, false, false, 4)) {
                    yield "single-pricing-white";
                }
                yield "\">
                <div class=\"price-head\">
                    <h2>";
                // line 6
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "name", [], "any", false, false, false, 6), "html", null, true);
                yield "</h2>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                ";
                // line 14
                if (CoreExtension::getAttribute($this->env, $this->source, $context["row"], "is_highlighted", [], "any", false, false, false, 14)) {
                    // line 15
                    yield "                    <span class=\"price-label\">Best</span>
                ";
                }
                // line 17
                yield "                <h1 class=\"price\">\$";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "price", [], "any", false, false, false, 17), "html", null, true);
                yield "</h1>
                <h5>";
                // line 18
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "interval", [], "any", false, false, false, 18), "html", null, true);
                yield "ly</h5>
                ";
                // line 19
                if (CoreExtension::getAttribute($this->env, $this->source, $context["row"], "description", [], "any", false, false, false, 19)) {
                    // line 20
                    yield "                    <div style=\"height: 100px\" class=\"my-3 p-4\">
                            ";
                    // line 21
                    yield CoreExtension::getAttribute($this->env, $this->source, $context["row"], "description", [], "any", false, false, false, 21);
                    yield "
                    </div>
                ";
                }
                // line 24
                yield "                ";
                if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "user_subscription", [], "any", false, false, false, 24), "membership_plan_id", [], "any", false, false, false, 24) == CoreExtension::getAttribute($this->env, $this->source, $context["row"], "id", [], "any", false, false, false, 24))) {
                    // line 25
                    yield "                <button class=\"text-decoration-none btn-secondary\" disabled>Selected</button>
                ";
                } else {
                    // line 27
                    yield "                <a class=\"text-decoration-none\" href=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("get-membership", ["type" => CoreExtension::getAttribute($this->env, $this->source, $context["row"], "id", [], "any", false, false, false, 27)]), "html", null, true);
                    yield "\">";
                    if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "user_subscription", [], "any", false, false, false, 27), "membership_plan_id", [], "any", false, false, false, 27)) {
                        yield " Switch ";
                    } else {
                        yield "Select Plan";
                    }
                    yield "</a>
                ";
                }
                // line 29
                yield "            </div>
        </div><!--- END COL -->
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['row'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "partials/widgets/membership-table.twig";
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
        return array (  122 => 29,  110 => 27,  106 => 25,  103 => 24,  97 => 21,  94 => 20,  92 => 19,  88 => 18,  83 => 17,  79 => 15,  77 => 14,  66 => 6,  59 => 4,  49 => 3,  44 => 2,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "partials/widgets/membership-table.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\partials\\widgets\\membership-table.twig");
    }
}
