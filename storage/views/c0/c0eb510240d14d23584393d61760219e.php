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

/* partials/widgets/document_filter_tab.twig */
class __TwigTemplate_a9726f11716b66d937227da8b0bf3281 extends Template
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
        yield "<section class=\"fullwidth margin-top-65 padding-top-75 padding-bottom-70\" data-background-color=\"#f8f8f8\">

    <div class=\"container-fluid padding-left-0 padding-right-0\">
\t<div class=\"grid-container clearfix\">
            <div class=\"col-md-12\">
                <div id=\"shop\" class=\"row clearfix justify-content-center\">

                    <div class=\"style-3 text-center\">
                        <!-- Tabs Navigation -->
                        <ul class=\"tabs-nav\">
                            <li class=\"active\"><a href=\"#tab_new_release\">WHAT'S NEW?</a></li>
                            <li><a href=\"#tab_magazines\">MAGAZINES (";
        // line 12
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "allMagazines", [], "any", false, false, false, 12)), "html", null, true);
        yield ")</a></li>
                            <li><a href=\"#tab_coming\">E-BOOKS (";
        // line 13
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "ebooks", [], "any", false, false, false, 13)), "html", null, true);
        yield ")</a></li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class=\"tabs-container\">
                            <div class=\"tab-content\" id=\"tab_new_release\">
                                <div class=\"swiper px-4\" style=\"width: 95vw;\">
                                    <!-- Additional required wrapper -->
                                    <div class=\"swiper-wrapper\">
                                        <!-- Slides -->
                                        ";
        // line 23
        yield from $this->loadTemplate("partials/widgets/new_releases.twig", "partials/widgets/document_filter_tab.twig", 23)->unwrap()->yield($context);
        // line 24
        yield "                                    </div>
                                    <!-- If we need pagination -->
                                    <div class=\"swiper-pagination\"></div>

                                    <!-- If we need navigation buttons -->
                                    <div class=\"swiper-button-prev\"></div>
                                    <div class=\"swiper-button-next\"></div>

                                    <!-- If we need scrollbar -->
";
        // line 34
        yield "                                </div>

                            </div>

                            <div class=\"tab-content\" id=\"tab_magazines\">
                                ";
        // line 39
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "allMagazines", [], "any", false, false, false, 39)) > 0)) {
            // line 40
            yield "                                <div class=\"swiper px-4\" style=\"width: 95vw;\">
                                    <!-- Additional required wrapper -->
                                    <div class=\"swiper-wrapper\">
                                        ";
            // line 43
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "allMagazines", [], "any", false, false, false, 43));
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
            foreach ($context['_seq'] as $context["_key"] => $context["document"]) {
                // line 44
                yield "                                            <div class=\"swiper-slide\">
                                            ";
                // line 45
                yield from $this->loadTemplate("partials/widgets/ebookrow.twig", "partials/widgets/document_filter_tab.twig", 45)->unwrap()->yield($context);
                // line 46
                yield "                                            </div>
                                        ";
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
            unset($context['_seq'], $context['_key'], $context['document'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 48
            yield "                                    </div>
                                    <!-- If we need pagination -->
                                    <div class=\"swiper-pagination\"></div>
                                    <!-- If we need navigation buttons -->
                                    <div class=\"swiper-button-prev\"></div>
                                    <div class=\"swiper-button-next\"></div>

                                    <!-- If we need scrollbar -->
                                    ";
            // line 57
            yield "                                </div>
                                ";
        } else {
            // line 59
            yield "                                    <div style=\"display:block;padding:40px;width:100%;margin:0 auto;text-align:center;\">
                                        <h1><i class=\"sl sl-icon-layers\"></i></h1>
                                        <h3>No Magazine to show. Check back again.</h3>
                                    </div>
                                ";
        }
        // line 64
        yield "
                            </div>

                            <div class=\"tab-content\" id=\"tab_coming\">

                                ";
        // line 69
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "ebooks", [], "any", false, false, false, 69)) > 0)) {
            // line 70
            yield "                                    <div class=\"swiper px-4\" style=\"width: 95vw;\">
                                            <div class=\"swiper-wrapper\">
                                        ";
            // line 72
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "ebooks", [], "any", false, false, false, 72));
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
            foreach ($context['_seq'] as $context["_key"] => $context["document"]) {
                // line 73
                yield "                                            <!-- Listing Item -->
                                                <div class=\"swiper-slide\">
                                                ";
                // line 75
                yield from $this->loadTemplate("partials/widgets/ebookrow.twig", "partials/widgets/document_filter_tab.twig", 75)->unwrap()->yield($context);
                // line 76
                yield "                                                </div>
                                            <!-- Listing Item / End -->
                                        ";
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
            unset($context['_seq'], $context['_key'], $context['document'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 79
            yield "                                            </div>
                                        <!-- If we need pagination -->
                                        <div class=\"swiper-pagination\"></div>
                                        <!-- If we need navigation buttons -->
                                        <div class=\"swiper-button-prev\"></div>
                                        <div class=\"swiper-button-next\"></div>

                                        <!-- If we need scrollbar -->
                                        ";
            // line 88
            yield "                                    </div>
                                ";
        } else {
            // line 90
            yield "                                    <div style=\"display:block;padding:40px;width:100%;margin:0 auto;text-align:center;\">
                                        <h1><i class=\"sl sl-icon-book-open\"></i></h1>
                                        <h3>No E-Book to show. Check back again.</h3>
                                    </div>
                                ";
        }
        // line 95
        yield "
                            </div>
                        </div>

                    </div>


                </div>
            </div>

        </div>
    </div>

</section>
<!-- Fullwidth Section / End -->";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "partials/widgets/document_filter_tab.twig";
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
        return array (  230 => 95,  223 => 90,  219 => 88,  209 => 79,  193 => 76,  191 => 75,  187 => 73,  170 => 72,  166 => 70,  164 => 69,  157 => 64,  150 => 59,  146 => 57,  136 => 48,  121 => 46,  119 => 45,  116 => 44,  99 => 43,  94 => 40,  92 => 39,  85 => 34,  74 => 24,  72 => 23,  59 => 13,  55 => 12,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "partials/widgets/document_filter_tab.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\partials\\widgets\\document_filter_tab.twig");
    }
}
