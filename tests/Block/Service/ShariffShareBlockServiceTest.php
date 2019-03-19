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
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContext;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Test\AbstractBlockServiceTestCase;

final class ShariffShareBlockServiceTest extends AbstractBlockServiceTestCase
{
    public function testDefaultSettings(): void
    {
        $blockService = new ShariffShareBlockService('block.service', $this->templating);
        $blockContext = $this->getBlockContext($blockService);

        $this->assertSettings([
            'url'            => null,
            'class'          => null,
            'services'       => ['twitter', 'facebook', 'googleplus'],
            'theme'          => 'standard',
            'orientation'    => 'horizontal',
            'flattrUser'     => null,
            'flattrCategory' => null,
            'template'       => '@Core23Shariff/Block/block_shariff.html.twig',
        ], $blockContext);
    }

    public function testExecute(): void
    {
        $block = new Block();

        $blockContext = new BlockContext($block, [
            'url'            => null,
            'class'          => null,
            'services'       => ['twitter', 'facebook', 'googleplus'],
            'theme'          => 'standard',
            'orientation'    => 'horizontal',
            'flattrUser'     => null,
            'flattrCategory' => null,
            'template'       => '@Core23Shariff/Block/block_shariff.html.twig',
        ]);

        $blockService = new ShariffShareBlockService('block.service', $this->templating);
        $blockService->execute($blockContext);

        $this->assertSame('@Core23Shariff/Block/block_shariff.html.twig', $this->templating->view);

        $this->assertSame($blockContext, $this->templating->parameters['context']);
        $this->assertInternalType('array', $this->templating->parameters['settings']);
        $this->assertInstanceOf(BlockInterface::class, $this->templating->parameters['block']);
    }

    public function testGetBlockMetadata(): void
    {
        $blockService = new ShariffShareBlockService('block.service', $this->templating);

        $metadata = $blockService->getBlockMetadata('description');

        $this->assertSame('block.service', $metadata->getTitle());
        $this->assertSame('description', $metadata->getDescription());
        $this->assertNotNull($metadata->getImage());
        $this->assertStringStartsWith('data:image/png;base64,', $metadata->getImage() ?? '');
        $this->assertSame('Core23ShariffBundle', $metadata->getDomain());
        $this->assertSame([
            'class' => 'fa fa-share-square-o',
        ], $metadata->getOptions());
    }

    public function testBuildEditForm(): void
    {
        $blockService = new ShariffShareBlockService('block.service', $this->templating);

        $block = new Block();

        $formMapper = $this->createMock(FormMapper::class);
        $formMapper->expects($this->once())->method('add');

        $blockService->buildEditForm($formMapper, $block);
    }
}
