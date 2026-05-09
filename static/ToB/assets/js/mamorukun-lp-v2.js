(()=>{
  const initFaq=()=>{
    const items=[...document.querySelectorAll('.faq-item')];
    if(!items.length)return;

    items.forEach(item=>{
      const trigger=item.querySelector('.faq-q');
      if(!trigger)return;

      trigger.addEventListener('click',()=>{
        items.forEach(otherItem=>{
          if(otherItem!==item){
            otherItem.classList.remove('open');
          }
        });

        item.classList.toggle('open');
      });
    });
  };

  const animateHeroIntro=()=>{
    const heroContent=document.querySelector('.hero-ct');
    const heroScroll=document.querySelector('.hero-scroll');

    if(heroContent){
      heroContent.animate(
        [{opacity:0,transform:'translateY(20px)'},{opacity:1,transform:'translateY(0)'}],
        {duration:800,easing:'cubic-bezier(.22,1,.36,1)',fill:'both'}
      );
    }

    if(heroScroll){
      heroScroll.animate(
        [{opacity:0,transform:'translateX(-50%) translateY(20px)'},{opacity:1,transform:'translateX(-50%) translateY(0)'}],
        {duration:800,delay:180,easing:'cubic-bezier(.22,1,.36,1)',fill:'both'}
      );
    }
  };

  const initRevealAnimations=({prefersReducedMotion})=>{
    const elements=[...document.querySelectorAll('.reveal')];
    if(!elements.length)return;

    if(prefersReducedMotion){
      elements.forEach(element=>element.classList.add('v'));
      return;
    }

    elements.forEach((element,index)=>{
      element.style.transitionDelay=`${Math.min(index*60,420)}ms`;
    });

    const observer=new IntersectionObserver((entries,instance)=>{
      entries.forEach(entry=>{
        if(entry.isIntersecting){
          entry.target.classList.add('v');
          instance.unobserve(entry.target);
        }
      });
    },{threshold:.15,rootMargin:'0px 0px -8% 0px'});

    elements.forEach(element=>observer.observe(element));
    animateHeroIntro();
  };

  const initDemoSlider=({hoverCapable,prefersReducedMotion})=>{
    const slider=document.querySelector('[data-demo-slider]');
    if(!slider)return;

    const viewport=slider.querySelector('.demo-viewport');
    const track=slider.querySelector('.demo-grid');
    const prev=slider.querySelector('[data-demo-prev]');
    const next=slider.querySelector('[data-demo-next]');
    const dotsHost=slider.querySelector('[data-demo-dots]');
    const count=slider.querySelector('[data-demo-count]');
    const progress=slider.querySelector('[data-demo-progress]');

    if(!viewport||!track||!dotsHost)return;

    const cards=[...track.querySelectorAll('.demo-card')];
    if(!cards.length)return;

    const slideCount=cards.length;
    let activeIndex=1;
    let proximityFrame=0;
    let resizeTimer=0;
    let isAnimating=false;
    let pointerStartX=null;
    let pointerDeltaX=0;

    const firstClone=cards[0].cloneNode(true);
    const lastClone=cards[cards.length-1].cloneNode(true);
    firstClone.setAttribute('aria-hidden','true');
    lastClone.setAttribute('aria-hidden','true');
    track.appendChild(firstClone);
    track.insertBefore(lastClone,track.firstChild);

    const allCards=[...track.querySelectorAll('.demo-card')];
    const dotButtons=cards.map((_,index)=>{
      const button=document.createElement('button');
      button.type='button';
      button.className='demo-dot';
      button.setAttribute('aria-label',`${index+1}件目の参考サイトを見る`);
      dotsHost.appendChild(button);
      return button;
    });

    const getRealIndex=()=>((activeIndex-1+slideCount)%slideCount);

    const moveToSlide=(index,withTransition=true)=>{
      const cardWidth=viewport.clientWidth;
      track.style.transition=withTransition&&!prefersReducedMotion?'transform .55s cubic-bezier(.22,1,.36,1)':'none';
      track.style.transform=`translate3d(${-cardWidth*index}px,0,0)`;
    };

    const resetCardMotion=()=>{
      allCards.forEach((card,index)=>{
        const isActive=index===activeIndex;
        card.classList.toggle('is-active',isActive);
        card.style.setProperty('--demo-scale',isActive?'1.02':'0.96');
        card.style.setProperty('--demo-lift',isActive?'-8px':'6px');
        card.style.setProperty('--demo-glow',isActive?'.22':'0');
        card.style.setProperty('--demo-mouse-x','50%');
        card.style.setProperty('--demo-mouse-y','50%');
      });
    };

    const updateSlider=()=>{
      const realIndex=getRealIndex();

      dotButtons.forEach((button,index)=>{
        const isActive=index===realIndex;
        button.classList.toggle('is-active',isActive);
        button.setAttribute('aria-current',isActive?'true':'false');
      });

      if(count){
        count.textContent=`${String(realIndex+1).padStart(2,'0')} / ${String(slideCount).padStart(2,'0')}`;
      }

      if(progress){
        progress.style.width=`${((realIndex+1)/slideCount)*100}%`;
      }

      resetCardMotion();
    };

    const settleLoop=()=>{
      if(activeIndex===0){
        activeIndex=slideCount;
        moveToSlide(activeIndex,false);
      }else if(activeIndex===slideCount+1){
        activeIndex=1;
        moveToSlide(activeIndex,false);
      }

      isAnimating=false;
      updateSlider();
    };

    const finishMotion=()=>{
      if(prefersReducedMotion){
        settleLoop();
      }
    };

    const moveStep=direction=>{
      if(isAnimating)return;

      isAnimating=true;
      activeIndex+=direction;
      moveToSlide(activeIndex,true);
      updateSlider();
      finishMotion();
    };

    const setGlow=event=>{
      if(prefersReducedMotion||!hoverCapable)return;

      const activeCard=allCards[activeIndex];
      if(!activeCard)return;

      const rect=activeCard.getBoundingClientRect();
      const localX=((event.clientX-rect.left)/rect.width)*100;
      const localY=((event.clientY-rect.top)/rect.height)*100;

      activeCard.style.setProperty('--demo-scale','1.045');
      activeCard.style.setProperty('--demo-lift','-12px');
      activeCard.style.setProperty('--demo-glow','.34');
      activeCard.style.setProperty('--demo-mouse-x',`${Math.max(0,Math.min(100,localX))}%`);
      activeCard.style.setProperty('--demo-mouse-y',`${Math.max(0,Math.min(100,localY))}%`);
    };

    dotButtons.forEach((button,index)=>{
      button.addEventListener('click',()=>{
        if(activeIndex===index+1||isAnimating)return;

        activeIndex=index+1;
        isAnimating=true;
        moveToSlide(activeIndex,true);
        updateSlider();
        finishMotion();
      });
    });

    if(prev){
      prev.addEventListener('click',()=>moveStep(-1));
    }

    if(next){
      next.addEventListener('click',()=>moveStep(1));
    }

    viewport.addEventListener('keydown',event=>{
      if(event.key==='ArrowRight'){
        event.preventDefault();
        moveStep(1);
      }

      if(event.key==='ArrowLeft'){
        event.preventDefault();
        moveStep(-1);
      }
    });

    viewport.addEventListener('pointerdown',event=>{
      pointerStartX=event.clientX;
      pointerDeltaX=0;
    });

    viewport.addEventListener('pointermove',event=>{
      if(pointerStartX===null)return;

      pointerDeltaX=event.clientX-pointerStartX;
      if(hoverCapable&&!prefersReducedMotion){
        if(proximityFrame)cancelAnimationFrame(proximityFrame);
        proximityFrame=requestAnimationFrame(()=>setGlow(event));
      }
    });

    viewport.addEventListener('pointerup',()=>{
      if(Math.abs(pointerDeltaX)>56){
        moveStep(pointerDeltaX<0?1:-1);
      }

      pointerStartX=null;
      pointerDeltaX=0;
      resetCardMotion();
    });

    viewport.addEventListener('pointerleave',()=>{
      pointerStartX=null;
      pointerDeltaX=0;
      resetCardMotion();
    });

    if(hoverCapable&&!prefersReducedMotion){
      viewport.addEventListener('mousemove',event=>{
        if(proximityFrame)cancelAnimationFrame(proximityFrame);
        proximityFrame=requestAnimationFrame(()=>setGlow(event));
      });

      viewport.addEventListener('mouseleave',resetCardMotion);
    }

    track.addEventListener('transitionend',event=>{
      if(event.target!==track)return;
      settleLoop();
    });

    window.addEventListener('resize',()=>{
      clearTimeout(resizeTimer);
      resizeTimer=window.setTimeout(()=>{
        moveToSlide(activeIndex,false);
        updateSlider();
      },120);
    });

    moveToSlide(activeIndex,false);
    updateSlider();
  };

  const initBackToTop=({prefersReducedMotion})=>{
    const backToTop=document.querySelector('[data-back-to-top]');
    if(!backToTop)return;

    const toggleBackToTop=()=>{
      backToTop.classList.toggle('visible',window.scrollY>window.innerHeight*.65);
    };

    toggleBackToTop();
    window.addEventListener('scroll',toggleBackToTop,{passive:true});
    backToTop.addEventListener('click',()=>{
      window.scrollTo({top:0,behavior:prefersReducedMotion?'auto':'smooth'});
    });
  };

  const initPage=()=>{
    const media={
      prefersReducedMotion:window.matchMedia('(prefers-reduced-motion:reduce)').matches,
      hoverCapable:window.matchMedia('(hover:hover)').matches,
    };

    initFaq();
    initRevealAnimations(media);
    initDemoSlider(media);
    initBackToTop(media);
  };

  if(document.readyState==='loading'){
    document.addEventListener('DOMContentLoaded',initPage,{once:true});
  }else{
    initPage();
  }
})();
