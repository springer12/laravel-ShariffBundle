<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\ShariffBundle\Tests\Block\Service;

use Core23\ShariffBundle\Block\Service\ShariffShareBlockService;
use Sonata\BlockBundle\Block\BlockContext;
use Sonata\BlockBundle\Form\Mapper\FormMapper;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Test\BlockServiceTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ShariffShareBlockServiceTest extends BlockServiceTestCase
{
    public function testDefaultSettings(): void
    {
        $blockService = new ShariffShareBlockService($this->twig);
        $blockContext = $this->getBlockContext($blockService);

        $this->assertSettings([
            'url'            => null,
            'class'          => null,
            'services'       => ['twitter', 'facebook', 'googleplus'],
            'theme'          => 'standard',
            'orientation'    => 'horizontal',
            'template'       => '@Core23Shariff/Block/block_shariff.html.twig',
        ], $blockContext);
    }

    public function testExecute(): void
    {
        $block = new Block();

        $blockContext = new BlockContext(
            $block,
            [
                'url'         => null,
                'class'       => null,
                'services'    => ['twitter', 'facebook', 'googleplus'],
                'theme'       => 'standard',
                'orientation' => 'horizontal',
                'template'    => '@Core23Shariff/Block/block_shariff.html.twig',
            ]
        );

        $response = new Response();

        $this->twig->expects(static::once())->method('render')
            ->with(
                '@Core23Shariff/Block/block_shariff.html.twig',
                [
                    'context'  => $blockContext,
                    'settings' => $blockContext->getSettings(),
                    'block'    => $blockContext->getBlock(),
                ]
            )
            ->willReturn('TWIG_CONTENT')
        ;

        $blockService = new ShariffShareBlockService($this->twig);

        static::assertSame($response, $blockService->execute($blockContext, $response));
        static::assertSame('TWIG_CONTENT', $response->getContent());
    }

    public function testGetMetadata(): void
    {
        $blockService = new ShariffShareBlockService($this->twig);

        $metadata = $blockService->getMetadata();

        static::assertSame('core23_shariff.block.share', $metadata->getTitle());
        static::assertNull($metadata->getImage());
        static::assertSame('Core23ShariffBundle', $metadata->getDomain());
        static::assertSame([
            'class' => 'fa fa-share-square-o',
        ], $metadata->getOptions());
    }

    public function testConfigureEditForm(): void
    {
        $blockService = new ShariffShareBlockService($this->twig);

        $block = new Block();

        $formMapper = $this->createMock(FormMapper::class);
        $formMapper->expects(static::once())->method('add');

        $blockService->configureEditForm($formMapper, $block);
    }
}
