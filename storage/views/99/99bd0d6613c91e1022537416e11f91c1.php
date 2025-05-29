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

/* my-referrals.twig */
class __TwigTemplate_e75c72f509b144c2c68c54d5d26b64c9 extends Template
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
        // line 1
        return "admin/admin_layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("admin/admin_layout.twig", "my-referrals.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 3
        yield "<div class=\"row\">
\t\t\t<div class=\"col-lg-12\">
\t\t\t\t\t\t";
        // line 5
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "data", [], "any", false, false, false, 5)) {
            // line 6
            yield "\t\t\t\t\t\t<div class=\"table table-responsive\" style=\"margin-top:20px;\">
\t\t\t\t\t\t<table id=\"datatable1\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\">
\t\t\t\t\t\t\t<thead>
\t\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t\t<th>S.No.</th>
\t\t\t\t\t\t\t\t\t<th>Name</th>
\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t\t<th>Referral Code</th>
\t\t\t\t\t\t\t\t\t<th>Date Joined</th>
\t\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t\t</thead>
\t\t\t\t\t\t\t<tfoot>
\t\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t\t<th>S.No.</th>
\t\t\t\t\t\t\t\t\t<th>Name</th>
\t\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t\t<th>Referral Code</th>
\t\t\t\t\t\t\t\t\t<th>Date Joined</th>
\t\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t\t</tfoot>
\t\t\t\t\t\t\t<tbody>
\t\t\t\t\t\t\t ";
            // line 27
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "data", [], "any", false, false, false, 27));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                // line 28
                yield "\t\t\t\t\t\t<tr>
\t\t\t\t\t\t<td>";
                // line 29
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 29) + 1), "html", null, true);
                yield "</td>
\t\t\t\t\t\t<td>";
                // line 30
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "first_name", [], "any", false, false, false, 30), "html", null, true);
                yield " ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "last_name", [], "any", false, false, false, 30), "html", null, true);
                yield "</td>
\t\t\t\t\t\t
\t\t\t\t\t\t<td>";
                // line 32
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "referral_code", [], "any", false, false, false, 32), "html", null, true);
                yield "</td>
\t\t\t\t\t\t<td>";
                // line 33
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "date_created", [], "any", false, false, false, 33), "html", null, true);
                yield "</td>
\t\t\t\t\t\t\t\t</tr>
\t\t\t ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 36
            yield "\t\t\t\t\t
\t\t\t\t\t\t\t
\t\t\t\t\t\t\t</tbody>
\t\t\t\t\t\t</table>
\t\t\t\t\t</div> 
            ";
        } else {
            // line 42
            yield "\t\t\t<div style=\"text-align: center;padding: 40px 0;\">
\t\t\t\t<h1><i class=\"fa fa-code\"></i></h1>
\t\t\t\t<h3>No Connection Yet</h3>
\t\t\t\t\t
\t\t\t<h4 style=\"text-align:center;\">";
            // line 46
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "referal_name", [], "any", false, false, false, 46)) {
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "referal_name", [], "any", false, false, false, 46), "html", null, true);
                yield " referred you. ";
            }
            yield "You have ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "data", [], "any", false, false, false, 46)), "html", null, true);
            yield " connections since ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "date_joined", [], "any", false, false, false, 46), "html", null, true);
            yield ".</h4>
\t\t
\t\t\t\t<h4>Create referral codes that you can share with anyone. Your connections will be visible when someone subscribes using your reference.</h4>
\t\t\t\t<a href=\"";
            // line 49
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
            yield "/referral-codes\" class=\"button button-dark margin-top-20 margin-bottom-20\"><i class=\"fa fa-edit\"></i>Generate Referral Code</a>\t\t
\t\t\t</div>
            ";
        }
        // line 52
        yield "
\t\t\t\t\t<div class=\"clear\"></div>
\t\t\t\t\t
\t\t\t\t\t
\t\t\t</div>
\t\t</div>
\t\t
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "my-referrals.twig";
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
        return array (  172 => 52,  166 => 49,  153 => 46,  147 => 42,  139 => 36,  122 => 33,  118 => 32,  111 => 30,  107 => 29,  104 => 28,  87 => 27,  64 => 6,  62 => 5,  58 => 3,  51 => 2,  40 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "my-referrals.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\my-referrals.twig");
    }
}
