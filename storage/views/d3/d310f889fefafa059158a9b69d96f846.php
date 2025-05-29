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

/* referral-codes.twig */
class __TwigTemplate_2a80c88f5f29a5d18b58085e384b4fdb extends Template
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
            'slider' => [$this, 'block_slider'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "dashboard_layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("dashboard_layout.twig", "referral-codes.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_slider(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 4
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 5
        yield "    <div class=\"row\">
        ";
        // line 6
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "role_id", [], "any", false, false, false, 6) == 1)) {
            // line 7
            yield "            <div class=\"col-lg-6 col-md-6\">
                <section style=\"padding: 20px 20px 20px 20px;text-align: center;background-color:#ffffff;\"
                         class=\"center\">
                    <h1 style=\"text-align: center;font-weight: 500;font-size:50px;margin-top:0px;\"><i
                                class=\"im im-icon-Conference\"></i></h1>
                    <p style=\"padding-bottom: 3px;font-size:16px;line-height:16px;font-weight:500;\">Generate referrals
                        code that you can share with friends and family. Win reward points that you will redeem.</p>
                    <div class=\"text-center\">
                        <form role=\"form\" method=\"POST\" name=\"createReferralForm\" id=\"createReferralForm\"
                              action=\"";
            // line 16
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("create-referral-code"), "html", null, true);
            yield "\">
                            <input type=\"hidden\" value=\"";
            // line 17
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "userID", [], "any", false, false, false, 17), "html", null, true);
            yield "\" name=\"user_id\" id=\"user_id\">
                            <button class=\"button btn btn-purple text-white button-3d button-rounded button-small button-green\"
                                    type=\"submit\"><i class=\"fa fa-arrow-circle-right\"></i> Generate New Referral Code
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        ";
        }
        // line 26
        yield "        <div class=\"";
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "role_id", [], "any", false, false, false, 26) == 1)) {
            yield "col-lg-6 col-md-6";
        } else {
            yield "col-lg-12 col-md-12";
        }
        yield "\">
            <!--- Stats Start -->
            <div class=\"row\">
                <div class=\"col-lg-6 col-md-12\">
                    <a href=\"";
        // line 30
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/my-connections\">
                        <div class=\"dashboard-stat color-3\">
                            <div class=\"dashboard-stat-content\"><h4>";
        // line 32
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "num_connections", [], "any", false, false, false, 32), "html", null, true);
        yield " </h4> <span
                                        style=\"color:#ffffff;\">My Connections 2</span></div>
                            <div class=\"dashboard-stat-icon\"><i class=\"im im-icon-Conference\"></i></div>
                        </div>
                    </a>
                </div>

                <div class=\"col-lg-6 col-md-12\">
                    <a href=\"";
        // line 40
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/reward-points\">
                        <div class=\"dashboard-stat color-3\">
                            <div class=\"dashboard-stat-content\"><h4>";
        // line 42
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "reward_points", [], "any", false, false, false, 42), "html", null, true);
        yield " </h4> <span
                                        style=\"color:#ffffff;\">Reward Points</span></div>
                            <div class=\"dashboard-stat-icon\"><i class=\"im im-icon-Coins-3\"></i></div>
                        </div>
                    </a>
                </div>

            </div>
            <!-- Stats Ends -->
        </div>
    </div>


    <div class=\"clear\"></div>


    <div class=\"row\">
        <div class=\"col-lg-12 center\">
            <ul class=\"nav\" data-submenu-title=\"Your Referral Code\" style=\"gap:20px\">
                ";
        // line 61
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "referral_code", [], "any", false, false, false, 61)) {
            // line 62
            yield "                    <li class=\"nav-item\">
                        <a href=\"#\" class=\"text-decoration-none nav-link bg-dark text-white rounded-lg \"
                           onclick=\"copyToClipboard('";
            // line 64
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "referral_code", [], "any", false, false, false, 64), "html", null, true);
            yield "')\" id=\"referral-code\">
                            <i class=\"sl sl-icon-tag\"></i>
                            Referral Code: <span id=\"referral-code-text\">";
            // line 66
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "referral_code", [], "any", false, false, false, 66), "html", null, true);
            yield "</span>
                        </a>
                    </li>
                    <li class=\"nav-item\">
                        <a href=\"#\" class=\"text-decoration-none nav-link bg-dark text-white rounded-lg \"
                           onclick=\"copyToClipboard('";
            // line 71
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("register"), "html", null, true);
            yield "?referral_code=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "referral_code", [], "any", false, false, false, 71), "html", null, true);
            yield "')\"
                           id=\"referral-url\">
                            <i class=\"sl sl-icon-link\"></i>
                            Copy Referral URL
                        </a>
                    </li>
                ";
        } else {
            // line 78
            yield "                        <button href=\"#\" class=\"text-decoration-none btn btn-success\" onclick=\"generateReferralCode()\"
                           id=\"generate-referral-button\">
                            <i class=\"sl sl-icon-tag\"></i>
                            Generate Referral Code
                        </button>
                ";
        }
        // line 84
        yield "            </ul>

        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                console.log(text);
                navigator.clipboard.writeText(text)
                    .then(() => {
                        swal({
                            title: '',
                            html: 'Copied to clipboard '+ text,
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            focusConfirm: false
                        });
                    })
                    .catch(err => {
                        console.error('Error copying to clipboard:', err);
                        swal({
                            title: '',
                            html: 'Failed to copy. Please try again.',
                            type: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            focusConfirm: false
                        });
                    });
            } else {
                // Fallback for unsupported browsers
                const tempInput = document.createElement('input');
                tempInput.style.position = 'absolute';
                tempInput.style.left = '-9999px';
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                try {
                    document.execCommand('copy');
                    swal({
                        title: '',
                        html: 'Copied to clipboard',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        focusConfirm: false
                    });
                } catch (err) {
                    console.error('Fallback copy failed:', err);
                    swal({
                        title: '',
                        html: 'Failed to copy. Please try again.',
                        type: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        focusConfirm: false
                    });
                }
                document.body.removeChild(tempInput);
            }
        }

        function generateReferralCode() {
            fetch('";
        // line 149
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("generate-referral-code"), "html", null, true);
        yield "', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        swal({
                            title: '',
                            html: 'Referral code generated: ' + data.referral_code,
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            focusConfirm: false
                        });

                        // Update the DOM dynamically
                        const referralSection = document.querySelector('[data-submenu-title=\"Your Referral Code\"]');
                        referralSection.innerHTML = `
                <li class=\"nav-item\">
                    <a href=\"#\" class=\"text-decoration-none nav-link bg-dark text-white rounded-lg\" onclick=\"copyToClipboard('\${data.referral_code}')\" id=\"referral-code\">
                        <i class=\"sl sl-icon-tag\"></i>
                        Referral Code: <span id=\"referral-code-text\">\${data.referral_code}</span>
                    </a>
                </li>
            `;
                    } else {
                        swal({
                            title: '',
                            html: 'Failed to generate referral code. Please try again.',
                            type: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            focusConfirm: false
                        });
                    }
                })
                .catch(err => {
                    swal({
                        title: '',
                        html: 'An error occurred. Please try again.',
                        type: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        focusConfirm: false
                    });
                    console.error('Error generating referral code:', err);
                });
        }
    </script>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "referral-codes.twig";
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
        return array (  262 => 149,  195 => 84,  187 => 78,  175 => 71,  167 => 66,  162 => 64,  158 => 62,  156 => 61,  134 => 42,  129 => 40,  118 => 32,  113 => 30,  101 => 26,  89 => 17,  85 => 16,  74 => 7,  72 => 6,  69 => 5,  62 => 4,  52 => 2,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "referral-codes.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\referral-codes.twig");
    }
}
